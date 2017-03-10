<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Partners\PartnerHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\User, App\Models\DeviceUser;
use Illuminate\Http\Request,
    Route,
    Response,
    Validator,
    cURL,
    Cache,
    CommonHelper,
    App\Helpers\Logs\NiuLogHelper,
    App\Helpers\Logs\DauLogHelper,
    App\Helpers\Logs\UtilHelper,
    Mobile_Detect, Log;

class AuthenticationController extends Controller
{
    private function validateInput($request, $rules = [
        'access_token' => 'required',
        'client_id' => 'required|exists:oauth_clients,id|exists:merchant_app,clientid',
        'client_secret' => 'required|exists:oauth_clients,secret',
        'os_id' => 'integer|min:1',
//        'cpid' => 'integer',
    ])
    {
        //return ['error_code' => 403];

        $messages = [
            'required' => '401|The ":attribute" field is required.',
            'exists' => '402|The ":attribute" field is invalid.',
//            'integer' => '406|The ":attribute" field must be integer.',
            'min' => '407|The ":attribute" field must be at least :min.',
        ];

        $input = array_map('trim', $request->all());

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $error = explode('|', $errors[0]);

            return [
                'data' => [],
                'error_code' => (int)$error[0],
                'message' => $error[1]
            ];
        }

        if (isset($input['cpid']) && $input['cpid'] == 'vpc') {
            $input['cpid'] = 300000141;
        }

        if (!$request->has('cpid')) {
            $os_id = 3;

            $detect = new Mobile_Detect();
            if ($detect->isiOS()) {
                $os_id = 2;
            }

            if ($detect->isAndroidOS()) {
                $os_id = 1;
            }

            $input['cpid'] = UtilHelper::getDefaultCpid($os_id, $input['client_id']);
        }

        return $input;
    }

    private function response($user, $payload)
    {
        if ($user->active == 0)
        {
            return Response::json([
                'data' => [],
                'error_code' => 403,
                'message' => 'Your account is blocked.'
            ]);
        }

        $user = $user->toArray();

        $payload['grant_type'] = 'user';
        $payload['username'] = $user['name'];

        $request_token = CommonHelper::generateAccessToken($payload);

        if (isset($request_token['access_token'])) {

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

            $user_info = [];
            $user_info['username'] = $user['name'];

            foreach ($user_fields as $field) {
                $user_info[$field] = (isset($user[$field]) && !is_null($user[$field])) ? $user[$field] : '';
            }

//
            $ulog = [
                'cpid' => $payload['cpid'],
                'userid' => $user['id']
            ];

            $daulog = new DauLogHelper;
            $daulog->setDauLog($ulog);

            if (isset($payload['is_new'])) {
                $niulog = new NiuLogHelper;
                $niulog->setNiuLog($ulog);

                if (!empty($payload['cpid']))
                {
                    $sub_cpid = isset($payload['sub_cpid']) ? $payload['sub_cpid'] : 0;

                    $registered = PartnerHelper::register($user['id'], $payload['cpid'], $sub_cpid);
                    
                    if (!is_null($registered))
                    {
                        if (!$registered)
                        {
                            Log::info('Lỗi không đăng ký được người dùng [' . $user['id'] . '] từ CPID [' . $payload['cpid'] . '] - Sub CPID [' . $sub_cpid . ']');
                        } else {
                            $_user = User::find($user['id']);
                            $_user->provider = $registered['_provider'];

                            if (isset($payload['sub_cpid']) && !empty($payload['sub_cpid']))
                            {
                                $_user->sub_cpid = $payload['sub_cpid'];
                            }

                            $_user->save();
                        }
                    }
                }
            }
//
            if (!empty($payload['device_id']) && !empty($payload['os_id'])) {
                $device_user = DeviceUser::firstOrNew([
                    'uid' => $user['id'],
                    'os_id' => $payload['os_id'],
                    'client_id' => $payload['client_id'],
                ]);

                $device_user->device_id = $payload['device_id'];
                $device_user->save();
            }

            $apps = Cache::get('active_app_list');
            if (isset($apps[$payload['client_id']])) {
                UserHelper::logUserGame($user['id'], $apps[$payload['client_id']]);
            }

            return Response::json([
                'data' => array_merge($user_info, $request_token),
                'error_code' => 200,
                'message' => Cache::get('welcome_message_' . $payload['client_id'], 'Đăng nhập thành công.')
            ]);
        } else {
            return Response::json([
                'data' => [],
                'error_code' => 500,
                'message' => 'Could not generate the token.'
            ]);
        }
    }

    public function google(Request $request)
    {
        $input = $this->validateInput($request);

        if (isset($input['error_code'])) {
            return Response::json($input);
        }

        $t = 0;
        $result = [];

        get:
        if ($t < 3) {
            try {
                $_request = cURL::newRequest('get', 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $input['access_token'])
                    ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                    ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                    ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                    ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                    ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

                $response = $_request->send();

                $result = json_decode($response->body, true);
            } catch (\Exception $e) {
                $t++;
                goto get;
            }
        } else {
            $result['error'] = true;
        }

        if (isset($result['error'])) {
            return Response::json([
                'data' => [],
                'error_code' => 403,
                'message' => 'The "access_token" field is invalid or An error occurred while retrieving the user information.'
            ]);
        } else {
            $name = isset($result['user_id']) ? 'gg' . $result['user_id'] : $result['email'];

            $user = User::where('email', $result['email'])->first();

            if (!$user) {
                $user = User::where('name', $name)->first();
            }

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $result['email'],
                    'password' => bcrypt(str_random(8)),
                    'avatar' => CommonHelper::getRandomAvatar($input['client_id']),
                    'provider' => 'google',
                    'provider_id' => isset($result['user_id']) ? $result['user_id'] : $result['email'],
                    'active' => 1,
                ]);

                $input['is_new'] = true;
            }

            $input['email'] = $result['email'];

            return $this->response($user, $input);
        }
    }

    public function facebook(Request $request)
    {
        $input = $this->validateInput($request);

        if (isset($input['error_code'])) {
            return Response::json($input);
        }

        $t = 0;
        $result = [];

        get:
        if ($t < 3) {
            try {
                $_request = cURL::newRequest('get', 'https://graph.facebook.com/me?fields=name,email&access_token=' . $input['access_token'])
                    ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                    ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                    ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                    ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                    ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

                $response = $_request->send();

                $result = json_decode($response->body, true);
            } catch (\Exception $e) {
                $t++;
                goto get;
            }
        }

        if (!isset($result['id'])) {
            return Response::json([
                'data' => [],
                'error_code' => 403,
                'message' => 'The "access_token" field is invalid or An error occurred while retrieving the user information.'
            ]);
        }

        $name = 'fb' . $result['id'];

        $user = User::where('name', $name)->first();

        $email = isset($result['email']) ? $result['email'] : ($result['id'] . '@facebook.com');

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'fullname' => $result['name'],
                'email' => $email,
                'password' => bcrypt(str_random(8)),
                'avatar' => CommonHelper::getRandomAvatar($input['client_id']),
                'provider' => 'facebook',
                'provider_id' => $result['id'],
                'active' => 1,
            ]);

            $input['is_new'] = true;
        }

        $input['email'] = $email;

        return $this->response($user, $input);
    }

    public function device(Request $request)
    {
        $input = $this->validateInput($request, [
            'device_id' => 'required',
            'os_id' => 'required|integer|min:1',
            'client_id' => 'required|exists:oauth_clients,id|exists:merchant_app,clientid',
            'client_secret' => 'required|exists:oauth_clients,secret',
        ]);

        if (isset($input['error_code'])) {
            return Response::json($input);
        }

        $name = md5($input['client_id'] . $input['device_id']);
        $email = $name . '@slg.vn';

        $user = User::where('name', $name)->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt(str_random(8)),
                'provider' => 'device',
                'provider_id' => $input['device_id'],
                'avatar' => CommonHelper::getRandomAvatar($input['client_id']),
                'active' => 1,
            ]);

            $input['is_new'] = true;
        }

        $input['email'] = $email;

        return $this->response($user, $input);
    }

    public function storeDeviceToken(Request $request)
    {
        $input = $this->validateInput($request, [
            'uid' => 'required',
            'device_id' => 'required',
            'os_id' => 'required|integer|min:1',
            'client_id' => 'required|exists:oauth_clients,id|exists:merchant_app,clientid',
        ]);

        if (isset($input['error_code'])) {
            return Response::json($input);
        }

        $device_user = DeviceUser::firstOrNew([
            'uid' => $input['uid'],
            'os_id' => $input['os_id'],
            'client_id' => $input['client_id'],
        ]);

        $device_user->device_id = $input['device_id'];
        $device_user->save();

        return Response::json([
            'data' => [],
            'error_code' => 200,
            'message' => 'The device token has been stored.'
        ]);
    }
}
