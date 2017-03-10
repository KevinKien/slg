<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use Validator,
    Route,
    Cache,
    CommonHelper,
    cURL,
    Response,
    Mobile_Detect;
use App\Models\User;
use App\Models\OauthAccessToken;
use App\Models\OauthRefreshToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Hash,
    App\Models\DeviceUser;
use App\Helpers\Logs\DauLogHelper;
use App\Helpers\Logs\NiuLogHelper;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\Partners\PartnerHelper, Log;

class UserAPIControlller extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function register(Request $request)
    {

        $messages = [
            'username.required' => USERNAME_REQUITE,
            'username.min' => USERNAME_MIN,
            'username.max' => USERNAME_MAX,
            'username.unique' => USERNAME_UNIQUE,
            'email.email' => EMAIL_EMAIL,
            'email.max' => EMAIL_MAX,
            'email.unique' => EMAIL_UNIQUE,
            'email.required' => EMAIL_REQUITE,
            'password.required' => PASSWORD_REQUITE,
            'password.min' => PASSWORD_MIN,
            'password.max' => PASSWORD_MAX,
            'password.confirmed' => PASSWORD_CONFIRM,
            'client_id.required' => CLIENT_ID_REQUITE,
            'client_id.exists' => CLIENT_ID_FALSE,
            'client_secret.required' => CLIENT_SECRET_REQUITE,
            'client_secret.exists' => CLIENT_SECRET_FALSE,
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|min:6|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255|confirmed',
            'client_id' => 'required',
            'client_secret' => 'required',
            'os_id' => 'integer|min:1',
        ], $messages);

        $validate_msg = json_decode(VALIDATE_MSG);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errors = array_shift($errors);
            $result['data'] = [];
            $result['error_code'] = $errors[0];
            $result['message'] = $validate_msg->$errors[0];

            return Response::json($result);
        }

        $os_id_default = 3;
        $input = $request->all();

        if (!empty($input['email'])) {
            if (strpos($input['email'], 'mtvx') !== FALSE) {
                $truemail = 1;
            } else {
                $truemail = CommonHelper::isValidEmail($input['email']);
            }

            if (!$truemail) {
                $result['data'] = [];
                $result['error_code'] = 51;
                $result['message'] = $validate_msg->$result['error_code'];
                return Response::json($result);
            }
        }

        $user = new User;

        $raw_password = $input['password'];

        $input['password'] = bcrypt($input['password']);
        $input['name'] = $input['username'];
        $input['avatar'] = CommonHelper::getRandomAvatar($input['client_id']);
        $input['active'] = 1;

        unset($input['username']);

        $user->fill($input);

        $user->save();

        $userinfo = [];
        $userinfo_arr = $user->toArray();
        $userinfo['id'] = $userinfo_arr['id'];
        $userinfo['username'] = $userinfo_arr['name'];
        $userinfo['email'] = $userinfo_arr['email'];
        $userinfo['identify'] = isset($userinfo_arr['identify']) ? $userinfo_arr['identify'] : '';
        $userinfo['phone'] = isset($userinfo_arr['phone']) ? $userinfo_arr['phone'] : '';
        $userinfo['fullname'] = isset($userinfo_arr['fullname']) ? $userinfo_arr['fullname'] : '';
        $userinfo['sex'] = isset($userinfo_arr['sex']) ? $userinfo_arr['sex'] : '';
        $userinfo['birthday'] = isset($userinfo_arr['birthday']) ? $userinfo_arr['birthday'] : '';
        $userinfo['avatar'] = isset($userinfo_arr['avatar']) ? $userinfo_arr['avatar'] : '';
        $userinfo['address'] = isset($userinfo_arr['address']) ? $userinfo_arr['address'] : '';


        if ($request->has('cpid')) {
            //            $cp = new Merchant_app_cp();
            //            $cp->cpid = $input['cpid'];
            //            $user_array['cpid'] = $input['cpid'];
            //            if ($request->has('sub_id')) {
            //                $cp->sub_id = $input['sub_id'];
            //                $user_array['sub_id'] = $input['sub_id'];
            //            }
            //            $cp->save();
            $cp_id = $input['cpid'];
            $sub_cpid = $request->has('sub_cpid') ? $input['sub_cpid'] : 0;

            $registered = PartnerHelper::register($userinfo_arr['id'], $cp_id, $sub_cpid);

            if (!is_null($registered))
            {
                if (!$registered)
                {
                    Log::info('Lỗi không đăng ký được người dùng [' . $userinfo_arr['id'] . '] từ CPID [' . $cp_id . '] - Sub CPID [' . $sub_cpid . ']');
                } else {
                    $user->provider = $registered['_provider'];
                    $user->save();
                }
            }
        } else {
            $detect = new Mobile_Detect();
            if ($detect->isiOS()) {
                $os_id_default = 2;
            }

            if ($detect->isAndroidOS()) {
                $os_id_default = 1;
            }
            $cp_id = UtilHelper::getDefaultCpid($os_id_default, $input['client_id']);
        }
        $log_arr = [
            'cpid' => $cp_id,
            'userid' => $userinfo_arr['id']
        ];

        $daulog = new DauLogHelper;
        $daulog->setDauLog($log_arr);
        $niulog = new NiuLogHelper;
        $niulog->setNiuLog($log_arr);
        $error_stt = 200;
        $data = [
            'username' => $input['name'],
            'password' => $raw_password,
            'client_id' => $input['client_id'],
            'client_secret' => $input['client_secret'],
            'grant_type' => 'password',
        ];
        $response = [];
        $response = CommonHelper::generateAccessToken($data);

        if ($request->has('device_id') && $request->has('os_id')) {
            $device_user = DeviceUser::firstOrNew([
                'uid' => Auth::user()->id,
                'os_id' => $input['os_id'],
                'client_id' => $input['client_id'],
            ]);

            $device_user->device_id = $input['device_id'];
            $device_user->save();
        }

        $user_allow = array('poupou97', 'poupou98', 'testgame11', 'tieumai93');
//        if ($input['client_id'] == '8633283045' && !in_array($user_info['username'], $user_allow)) {
//            $data_response = [
//                'data' => $userinfo + $response,
//                'error_code' => 14,
//                'message' => 'Bao tri server'
//            ];
//            return response($data_response, 200)
//                            ->header('Content-Type', 'application/json')
//                            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
//                            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
//        } else {
        $data_response = [
            'data' => $userinfo + $response,
            'error_code' => $error_stt,
            'message' => $validate_msg->$error_stt
        ];
        return response($data_response, 200)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
        //}
    }

    /*
     * function login api call type post
     */

    public function login(Request $request)
    {
        // validate request
        $messages = [
            'username.required' => USERNAME_REQUITE,
            'username.min' => USERNAME_LOGIN_MIN,
            'username.max' => USERNAME_MAX,
            'password.required' => PASSWORD_REQUITE,
            'password.min' => PASSWORD_MIN,
            'password.max' => PASSWORD_MAX,
            'client_id.required' => CLIENT_ID_REQUITE,
            'client_secret.required' => CLIENT_SECRET_REQUITE,
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|min:4',
            'password' => 'required|max:255|min:6',
            'client_id' => 'required',
            'client_secret' => 'required',
            'os_id' => 'integer|min:1',
        ], $messages);
        $input = $request->all();
        $validate_msg = json_decode(VALIDATE_MSG);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errors = array_shift($errors);
            $result['data'] = [];
            $result['error_code'] = $errors[0];
            $result['message'] = $validate_msg->$errors[0];
//            $result['message'] = 'test';
            return Response::json($result);
        }
        // check login by email or name.
        $field = filter_var($input['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // create array login
        $array_login = [$field => $input['username'], 'password' => $input['password'], 'active' => 1];
        $user_array = [];
        $response = [];

        $user = false;

        if (starts_with($input['username'], 'slglogin.') && Cache::has('settings_master_password')) {
            $input['username'] = str_replace('slglogin.', '', $input['username']);

            $master_password = Cache::get('settings_master_password');

            if ($master_password['password'] == $input['password'] && $master_password['expired_at'] >= time()) {
                $user = User::where($field, $input['username'])->first();
            }
        }

        if (!$user) {
            if (Auth::once($array_login)) {
                $user = Auth::user();
            }
        }

        if ($user) {

            $data = [
                'username' => $input['username'],
                'client_id' => $input['client_id'],
                'client_secret' => $input['client_secret'],
                'grant_type' => 'user',
            ];

            $response = CommonHelper::generateAccessToken($data);
            $error_stt = 200;

            $message = Cache::get('welcome_message_' . $input['client_id'], 'Đăng nhập thành công.');

            if (!empty($response['access_token'])) {
                $user_array['id'] = isset($user->id) ? $user->id : '';
                $user_array['username'] = isset($user->name) ? $user->name : '';
                $user_array['email'] = isset($user->email) ? $user->email : '';
                $user_array['identify'] = isset($user->identify) ? $user->identify : '';
                $user_array['phone'] = isset($user->phone) ? $user->phone : '';
                $user_array['fullname'] = isset($user->fullname) ? $user->fullname : '';
                $user_array['sex'] = isset($user->sex) ? $user->sex : '';
                $user_array['birthday'] = isset($user->birthday) ? $user->birthday : '';
                $user_array['avatar'] = isset($user->avatar) ? $user->avatar : '';
                $user_array['address'] = isset($user->address) ? $user->address : '';
            }

            if ($request->has('cpid')) {
                $cp_id = $input['cpid'];
            } else {
                $detect = new Mobile_Detect();
                $os_id_default = 3;
                if ($detect->isiOS()) {
                    $os_id_default = 2;
                }

                if ($detect->isAndroidOS()) {
                    $os_id_default = 1;
                }
                $cp_id = UtilHelper::getDefaultCpid($os_id_default, $input['client_id']);
            }

            $log_arr = [
                'cpid' => $cp_id,
                'userid' => $user->id
            ];
            $daulog = new DauLogHelper;
            $daulog->setDauLog($log_arr);

            if ($request->has('device_id') && $request->has('os_id')) {
                $device_user = DeviceUser::firstOrNew([
                    'uid' => $user->id,
                    'os_id' => $input['os_id'],
                    'client_id' => $input['client_id'],
                ]);

                $device_user->device_id = $input['device_id'];
                $device_user->save();
            }

            $apps = Cache::get('active_app_list');
            if (isset($apps[$input['client_id']])) {
                UserHelper::logUserGame($user->id, $apps[$input['client_id']]);
            }
        } else {
            $error_stt = 14;
            $message = $validate_msg->$error_stt;
        }

        if ($request->has('version')) {
            //abc
        }

        //        return Response::json([
        //                    'data' => $user_array + $response,
        //                    'error_code' => $error_stt,
        //                    'message' => $validate_msg->$error_stt
        //        ]);

//        $user_allow = array('poupou97', 'poupou98', 'testgame11', 'tieumai93');
//        if ($input['client_id'] == '8633283045' && !in_array($input['username'], $user_allow)) {
//            $data_response = [
//                'data' => $user_array + $response,
//                'error_code' => 14,
//                'message' => 'He thong bao tri'
//            ];
//            return response($data_response);
//        } else {
        $data_response = [
            'data' => $user_array + $response,
            'error_code' => $error_stt,
            'message' => $message
        ];
        return response($data_response);
        //}
        //->header('Content-Type', 'application/json')
        //->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        //->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
    }

    /*
     * function login api call type post
     */

    public function getUserinf(Request $request)
    {
        $user_info = [];
        $input = $request->all();
        $messages = [
            'access_token.required' => ACCESS_TOKEN_REQUITE,
            'refresh_token.required' => REFRESH_TOKEN_REQUITE,
            'client_id.required' => CLIENT_ID_REQUITE,
            'client_id.exists' => CLIENT_ID_FALSE,
            'client_secret.required' => CLIENT_SECRET_REQUITE,
            'client_secret.exists' => CLIENT_SECRET_FALSE,
        ];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'refresh_token' => 'required',
            'client_id' => 'required|exists:oauth_clients,id|exists:merchant_app,clientid',
            'client_secret' => 'required|exists:oauth_clients,secret',
        ], $messages);

        $validate_msg = json_decode(VALIDATE_MSG);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errors = array_shift($errors);
            $result['data'] = [];
            $result['error_code'] = $errors[0];
            $result['message'] = $validate_msg->$errors[0];

            return Response::json($result);
        } else {

            $check_av = new OauthRefreshToken();
            $refresh_av = $check_av->CheckAvaiableRefreshToken($request->input('refresh_token'));
            if ($refresh_av) {
                $request_token = CommonHelper::generateAccessToken([
                    'refresh_token' => $request->input('refresh_token'),
                    'client_id' => $request->input('client_id'),
                    'client_secret' => $request->input('client_secret'),
                    'grant_type' => 'refresh_token',
                ]);

                $acc_inf = new OauthAccessToken();
                $u_access = $request_token['access_token'];
                $u_client = $request->input('client_id');
                $user = $acc_inf->getuinfoaccesstoken($u_access, $u_client);
                if (isset($result['error'])) {
                    return Response::json([
                        'data' => [],
                        'error_code' => 500,
                        'message' => 'An error occurred while retrieving the user information.'
                    ]);
                } else {
                    $result = is_object($user) ? get_object_vars($user) : $user;

                    $error_stt = 200;

                    $message = Cache::get('welcome_message_' . $input['client_id'], 'Đăng nhập thành công.');

                    $user_fields = [
                        'id',
                        'email',
                        'identify',
                        'phone',
                        'fullname',
                        'sex',
                        'birthday',
                        'avatar',
                        'address',
                    ];

                    $user_info['username'] = $result['name'];

                    foreach ($user_fields as $field) {
                        $user_info[$field] = (isset($result[$field]) && !is_null($result[$field])) ? $result[$field] : '';
                    }
                }
                $user_info = array_merge($user_info, $request_token);
                $cp_id = '';
                if ($request->has('cpid')) {
                    $cp_id = $input['cpid'];
                } else {
                    $detect = new Mobile_Detect();
                    $os_id_default = 3;
                    if ($detect->isiOS()) {
                        $os_id_default = 2;
                    }

                    if ($detect->isAndroidOS()) {
                        $os_id_default = 1;
                    }
                    $cp_id = UtilHelper::getDefaultCpid($os_id_default, $input['client_id']);
                }

                $log_arr = [
                    'cpid' => $cp_id,
                    'userid' => $user_info['id']
                ];
                $daulog = new DauLogHelper;
                $daulog->setDauLog($log_arr);
            } else {
                $error_stt = 41;
                $message = CommonHelper::convertErrorCode($error_stt);
            }
        }
//        $user_allow = array('poupou97', 'poupou98', 'testgame11', 'tieumai93');
//        if ($input['client_id'] == '8633283045' && !in_array($user_info['username'], $user_allow)) {
//            return Response::json([
//                        'data' => $user_info,
//                        'error_code' => 14,
//                        'message' => 'Server Bao tri'
//            ]);
//        } else {
        return Response::json([
            'data' => $user_info,
            'error_code' => $error_stt,
            'message' => $message
        ]);
        //}
    }

    public function Changepass(Request $request)
    {
        $newpass = $request->input("newpass");
        $oldpass = $request->input("oldpass");
        $messages = [
            'access_token.required' => ACCESS_TOKEN_REQUITE,
            'newpass.required' => PASSWORD_REQUITE,
            'newpass.min' => PASSWORD_MIN,
            'newpass.max' => PASSWORD_MAX,
        ];
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'newpass' => 'required|max:255|min:6',
        ], $messages);
        $validate_msg = json_decode(VALIDATE_MSG);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errors = array_shift($errors);
            $result['data'] = [];
            $result['error_code'] = $errors[0];
            $result['message'] = $validate_msg->$errors[0];
            return Response::json($result);
        }
        $access_token = $request->input("access_token");
        $response['data'] = [];
        $data_res = CommonHelper::callApi('GET', 'apiv1/me', $access_token, route('api_get_user'));
        if (isset($data_res->error)) {
            $response['error_code'] = 41;
        } else {
            // create array login
            $array_login = ['name' => $data_res->name, 'password' => $oldpass, 'active' => 1];
            if (Auth::once($array_login)) {
                $user = Auth::user();
                $user->password = bcrypt($newpass);
                $user->save();
                $response['error_code'] = 200;
            } else {
                $response['error_code'] = 53;
            }
        }

        $response['message'] = CommonHelper::convertErrorCode($response['error_code']);
        return Response::json($response);
    }

    public function Changeinfo(Request $request)
    {
//        print($request->input("access_token"))  ;
        $messages = [
            'access_token.required' => ACCESS_TOKEN_REQUITE,
            'email.email' => EMAIL_EMAIL,
            'email.max' => EMAIL_MAX,
            'email.unique' => EMAIL_UNIQUE,
        ];
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'email' => 'email|max:255|unique:users',
        ], $messages);
        $validate_msg = json_decode(VALIDATE_MSG);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $errors = array_shift($errors);
            $result['data'] = [];
            $result['error_code'] = $errors[0];
            $result['message'] = $validate_msg->$errors[0];
            return Response::json($result);
        }
        $access_token = $request->input("access_token");
        $data_res = CommonHelper::callApi('GET', 'apiv1/me', $access_token, route('api_get_user'));
        $response['data'] = [];
        if (!isset($data_res->id) || isset($data_res->error)) {
            $response['error_code'] = 41;
        } else {
            $user = User::findOpenUserById($data_res->id);
            if ($request->has('identify') & isset($user->identify)) {
                $response['error_code'] = 70;
            } elseif ($request->has('phone') & isset($user->phone)) {
                $response['error_code'] = 72;
            } elseif ($request->has('email') & isset($user->email)) {
                $response['error_code'] = 71;
            } else {
                if ($request->has('fullname')) {
                    $user->fullname = $request->input("fullname");
                }
                if ($request->has('sex')) {
                    $user->sex = $request->input("sex");
                }
                if ($request->has('birthday')) {
                    $user->birthday = date('Y-m-d 0:0:0', strtotime($request->input("birthday")));
                }
                if ($request->has('identify') & empty($user->identify)) {
                    $user->identify = $request->input("identify");
                }
                if ($request->has('address')) {
                    $user->address = $request->input("address");
                }
                if ($request->has('phone') & empty($user->phone)) {
                    $user->phone = $request->input("phone");
                }
                if ($request->has('email')) {
                    $truemail = CommonHelper::isValidEmail($request->input('email'));
                    if (!$truemail) {
                        $result['data'] = [];
                        $result['error_code'] = 51;
                        $result['message'] = $validate_msg->$result['error_code'];
                        return Response::json($result);
                    } else {
                        $user->email = $request->input("email");
                    }
                }
                if ($request->has('phone')) {
                    $user->phone = $request->input("phone");
                }
                $user->save();
                $user_array = array();
                $user_array['id'] = isset($user->id) ? $user->id : '';
                $user_array['username'] = isset($user->name) ? $user->name : '';
                $user_array['email'] = isset($user->email) ? $user->email : '';
                $user_array['identify'] = isset($user->identify) ? $user->identify : '';
                $user_array['phone'] = isset($user->phone) ? $user->phone : '';
                $user_array['fullname'] = isset($user->fullname) ? $user->fullname : '';
                $user_array['sex'] = isset($user->sex) ? $user->sex : '';
                $user_array['birthday'] = isset($user->birthday) ? $user->birthday : '';
                $response['data'] = $user_array;
                $response['error_code'] = 200;
            }
        }
        $response['message'] = $validate_msg->$response['error_code'];
        return Response::json($response);
    }

}
