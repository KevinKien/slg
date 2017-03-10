<?php

namespace App\Http\Controllers\Api\ThienLong\V1;

use App\Models\LogChargeTelco,
    App\Models\LogCoinTransfer;
use Route,
    Illuminate\Http\Request,
    Detection\MobileDetect,
    App\Helpers\Logs\UtilHelper;
use App\Http\Controllers\Controller,
    App\Helpers\Logs\DauLogHelper;
use App\Models\CashInfo,
    App\Models\OauthAccessToken,
    Validator,
    Auth;

class UserController extends Controller {

    public function getLogin(Request $request) {
        $_request = Request::create(route('user-login'), 'GET', $request->all());

        $response = json_decode(Route::dispatch($_request)->getContent(), true);

        if (!empty($response) && isset($response['error_code']) && $response['error_code'] == 200) {
            $cash = CashInfo::where('uid', $response['data']['id'])->first(['coins', 'point']);

            $coins = 0;
            $point = 0;

            if ($cash) {
                $coins = $cash->coins;
                $point = $cash->point;
            }

            $response['data'] = [
                'user_info' => [
                    'uid' => (string) $response['data']['id'],
                    'name' => $response['data']['username'],
                    'mail' => $response['data']['email'],
                    'fullname' => $response['data']['fullname'],
                    'birth' => $response['data']['birthday'],
                    'cardno' => $response['data']['identify'],
                    'address' => $response['data']['address'],
                    'Fpay' => $point,
                    'VND' => $coins,
                ],
                'access_token' => $response['data']['access_token'],
                'time_expired' => time() + (int) $response['data']['expires_in'],
            ];
        }

        return json_encode($response);
    }

    public function getRegister(Request $request) {
        $_request = Request::create(route('user-register'), 'GET', $request->all());

        $response = json_decode(Route::dispatch($_request)->getContent(), true);

        if (!empty($response) && isset($response['error_code']) && $response['error_code'] == 200) {
            $cash = CashInfo::where('uid', $response['data']['id'])->first(['coins', 'point']);

            $coins = 0;
            $point = 0;

            if ($cash) {
                $coins = $cash->coins;
                $point = $cash->point;
            }

            $response['data'] = [
                'user_info' => [
                    'uid' => (string) $response['data']['id'],
                    'name' => $response['data']['username'],
                    'mail' => $response['data']['email'],
                    'fullname' => $response['data']['fullname'],
                    'birth' => $response['data']['birthday'],
                    'cardno' => $response['data']['identify'],
                    'address' => $response['data']['address'],
                    'Fpay' => $point,
                    'VND' => $coins,
                ],
                'access_token' => $response['data']['access_token'],
                'time_expired' => time() + (int) $response['data']['expires_in'],
            ];
        }

        return json_encode($response);
    }

    public function getInformation(Request $request) {
        $validator = Validator::make($request->all(), [
                    'access_token' => 'required',
                    'client_id' => 'required',
        ]);

        $response = [
            'errorCode' => 42,
            'data' => '',
        ];

        if ($validator->fails()) {
            $response['errorCode'] = 2;
        } else {
            $oauth = new OauthAccessToken();
            $user = $oauth->getuinfoaccesstoken($request->input('access_token'), $request->input('client_id'));

            if ($user) {
                $cash = CashInfo::where('uid', $user->id)->first(['coins', 'point']);

                $coins = 0;
                $point = 0;

                if ($cash) {
                    $coins = $cash->coins;
                    $point = $cash->point;
                }

                $response['data'] = [
                    'uid' => (string) $user->id,
                    'name' => $user->name,
                    'mail' => $user->email,
                    'fullname' => $user->fullname,
                    'birth' => $user->birthday,
                    'cardno' => $user->identify,
                    'address' => $user->address,
                    'Fpay' => $point,
                    'VND' => $coins,
                ];

                $response['errorCode'] = 200;

                $detect = new MobileDetect();
                $os_id_default = 0;

                if ($detect->isiOS()) {
                    $os_id_default = 2;
                }

                if ($detect->isAndroidOS()) {
                    $os_id_default = 1;
                }

                $cp_id = UtilHelper::getDefaultCpid($os_id_default, $request->input('client_id'));

                $log_arr = [
                    'cpid' => $cp_id,
                    'userid' => $user->id
                ];

                $daulog = new DauLogHelper;
                $daulog->setDauLog($log_arr);
            }
        }

        return json_encode($response);
    }

    public function getChangePassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'access_token' => 'required',
                    'password' => 'required|min:6',
                    'client_id' => 'required',
        ]);

        $response = [
            'errorCode' => 42,
            'data' => '',
        ];

        if ($validator->fails()) {
            $response['errorCode'] = 2;
        } else {
            $oauth = new OauthAccessToken();
            $_user = $oauth->getuinfoaccesstoken($request->input('access_token'), $request->input('client_id'));

            if ($_user) {
                $user = Auth::onceUsingId($_user->id);
                $user->password = bcrypt($request->input('password'));
                $user->save();

                $response['errorCode'] = 200;
            }
        }

        return json_encode($response);
    }

    public function getChangeInformation(Request $request) {
        $validator = Validator::make($request->all(), [
                    'access_token' => 'required',
                    'client_id' => 'required',
                    'email' => 'required',
        ]);

        $response = [
            'errorCode' => 42,
            'data' => '',
        ];

        if ($validator->fails()) {
            $response['errorCode'] = 2;
        } else {
            $oauth = new OauthAccessToken();
            $_user = $oauth->getuinfoaccesstoken($request->input('access_token'), $request->input('client_id'));

            if ($_user) {
                $user = Auth::onceUsingId($_user->id);
                $user->email = $request->input('email');
                $user->fullname = $request->input('fullname');
                $user->identify = $request->input('identify');
                $user->address = $request->input('address');
                $user->save();

                $response['errorCode'] = 200;
            }
        }

        return json_encode($response);
    }

    public function getLoginFacebook(Request $request) {
        $_request = Request::create(route('user-login-facebook'), 'GET', $request->all());

        $response = json_decode(Route::dispatch($_request)->getContent(), true);

        if (!empty($response) && isset($response['error_code']) && $response['error_code'] == 200) {
            $cash = CashInfo::where('uid', $response['data']['id'])->first(['coins', 'point']);

            $coins = 0;
            $point = 0;

            if ($cash) {
                $coins = $cash->coins;
                $point = $cash->point;
            }

            $response['data'] = [
                'user_info' => [
                    'uid' => (string) $response['data']['id'],
                    'name' => $response['data']['username'],
                    'mail' => $response['data']['email'],
                    'fullname' => $response['data']['fullname'],
                    'birth' => $response['data']['birthday'],
                    'cardno' => $response['data']['identify'],
                    'address' => $response['data']['address'],
                    'Fpay' => $point,
                    'VND' => $coins,
                ],
                'access_token' => $response['data']['access_token'],
                'time_expired' => time() + (int) $response['data']['expires_in'],
            ];
        }

        return json_encode($response);
    }

//    public function getUpdateUserFacebook(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'access_token' => 'required',
//            'client_id' => 'required',
//            'username' => 'required',
//            'password' => 'required',
//        ]);
//
//        $response = [
//            'errorCode' => 42,
//            'data' => '',
//        ];
//
//        if ($validator->fails()) {
//            $response['errorCode'] = 2;
//        } else {
//            $oauth = new OauthAccessToken();
//            $_user = $oauth->getuinfoaccesstoken($request->input('access_token'), $request->input('client_id'));
//
//            if ($_user) {
//                $user = Auth::loginUsingId($_user->id);
//                $user->email = $request->input('email');
//                $user->fullname = $request->input('fullname');
//                $user->identify = $request->input('identify');
//                $user->address = $request->input('address');
//                $user->save();
//
//                $response['errorCode'] = 200;
//            }
//        }
//
//        return json_encode($response);
//    }

    public function getValidateAccessToken(Request $request) {
        $_request = Request::create(route('validate-access-token'), 'GET', $request->all());

        return Route::dispatch($_request)->getContent();
    }

    public function getTransactionLog(Request $request) {
        $validator = Validator::make($request->all(), [
                    'uid' => 'required',
                    'limit' => 'required|integer|min:1',
                    'app_id' => 'required|integer',
        ]);

        $response = [
            'errorCode' => 31,
            'data' => '',
        ];

        if ($validator->fails()) {
            $response['errorCode'] = 2;
        } else {
            $log_transfers = LogCoinTransfer::where('user_id', $request->input('uid'))
                    ->where('app_id', $request->input('app_id'))
                    ->orderBy('request_time', 'desc')
                    ->take($request->input('limit'))
                    ->get(['request_time', 'coin']);

            $log_charges = LogChargeTelco::where('uid', $request->input('uid'))
                    ->orderBy('created_at', 'desc')
                    ->take($request->input('limit'))
                    ->get(['created_at', 'amount', 'payment_status']);

            $response['data'] = [];

            foreach ($log_transfers as $log_transfer) {
                $response['data'][] = [
                    'time' => date('m/d/Y H:i:s', strtotime($log_transfer->request_time)),
                    'description' => 'Chuyển ' . $log_transfer->coin . ' Coins: ' . ($log_transfer->response == 'success' ? 'thành công' : 'thất bại'),
                    'app_name' => 'Thiên Long',
                    'type' => 'Chuyển Coin vào Game',
                ];
            }

            foreach ($log_charges as $log_charge) {
                $response['data'][] = [
                    'time' => date('m/d/Y H:i:s', strtotime($log_charge->created_at)),
                    'description' => 'Nạp ' . number_format($log_charge->amount, 0, ',', '.') . ' VND: ' . $log_charge->payment_status,
                    'app_name' => 'Thiên Long',
                    'type' => 'Nạp tiền vào ví',
                ];
            }

            $response['errorCode'] = 200;
        }

        return json_encode($response);
    }

    public function getLoginDevice(Request $request) {
        $_request = Request::create(route('login-device'), 'GET', $request->all());

        $response = json_decode(Route::dispatch($_request)->getContent(), true);

        if (!empty($response) && isset($response['error_code']) && $response['error_code'] == 200) {
            $cash = CashInfo::where('uid', $response['data']['id'])->first(['coins', 'point']);

            $coins = 0;
            $point = 0;

            if ($cash) {
                $coins = $cash->coins;
                $point = $cash->point;
            }

            $response['data'] = [
                'user_info' => [
                    'uid' => (string) $response['data']['id'],
                    'name' => $response['data']['username'],
                    'mail' => $response['data']['email'],
                    'fullname' => $response['data']['fullname'],
                    'birth' => $response['data']['birthday'],
                    'cardno' => $response['data']['identify'],
                    'address' => $response['data']['address'],
                    'Fpay' => $point,
                    'VND' => $coins,
                ],
                'access_token' => $response['data']['access_token'],
                'time_expired' => time() + (int) $response['data']['expires_in'],
            ];
        }

        return json_encode($response);
    }

}
