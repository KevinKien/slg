<?php

namespace App\Http\Controllers\Payment;

use App\Models\MerchantApp;
use DB;
use Validator;
use Response;
use Mobile_Detect;
use GameHelper;
use App\Models\User;
use App\Models\AppItems;
use App\Models\CashInfo;
use App\Models\LogChargeTelco;
use App\Models\LogCoinTransfer;
use App\Models\MerchantAppProductApple;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\LogController;
use App\Http\Controllers\Payment\Partner\TelcoEpay;
use App\Http\Controllers\Payment\Partner\AtmVisaOnepay;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\Games\GameServices;
use App\Helpers\Logs\RevenueLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class BuyItemMobileController extends Controller {

//protected $layout = 'payment.mobile.layout_payweb';

    public function __construct(Request $request) {
        $this->middleware('oauth', ['only' => ['get_index', 'getCallservice']]);
    }

    public function getList() {
        return CashInfo::all();
    }

    public function missingMethod($parameters = array()) {

        return view('errors.404');
    }

    public function get_index(Request $request) {
//dd($request); //2222222
// validate request
        $cpid = Input::has('cpid') ? Input::get('cpid') : '';    // optional
        $sub_cpid = Input::has('sub_cpid') ? Input::get('sub_cpid') : '';  // optional
        $client_id = Input::get('client_id');
        $server_id = Input::get('server_id');
        $access_token = Input::get('access_token');
        $refresh_token = Input::get('refresh_token');
        $sdk_version = Input::has('sdk_version') ? Input::get('sdk_version') : 0;

        $validator = Validator::make($request->all(), [
                    'client_id' => 'required',
                    'server_id' => 'required',
                    'access_token' => 'required',
                    'refresh_token' => 'required',]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return Response::json($errors);
        }

// validate access_token
        $resourceOwnerType = Authorizer::getResourceOwnerType();
        $resourceOwnerId = Authorizer::getResourceOwnerId();
        if ($resourceOwnerType === 'user') {
            $user = Auth::loginUsingId($resourceOwnerId);
        }

        $user = Auth::user();

        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $get_app_items = AppItems::getItemsByClientId($client_id);
        if (!is_array($get_app_items)) {
            return Response::json('Không tìm thấy danh sách vật phẩm phù hợp');
        }

        $topupcash_link = url('//pay.slg.vn/mtopupcash?refresh_token=' . $refresh_token .
                '&access_token=' . $access_token);
        $user_coins = CashInfo::getCoins();
        $callservice_link = url('//pay.slg.vn/mbuyitem/callservice?refresh_token=' . $refresh_token .
                '&access_token=' . $access_token .
                '&client_id=' . $client_id .
                '&server_id=' . $server_id .
                '&cpid=' . $cpid .
                '&sub_cpid=' . $sub_cpid);

// view for ios

        if ($this->checkIosDevice()) {
//check inreview app status
            if (!empty($sdk_version)) {
                $inreview_status = GameHelper::isInReview($client_id, $sdk_version);
//dd($inreview_status);
            } else {
                $inreview_status = false;
            }
//            if ($inreview_status) {
//                dd($request);
//            }
// get list apple item buy client id
            $itemlist = MerchantAppProductApple::get_products_apple_by_client_id($client_id);
            $callserviceapple_link = url('//pay.slg.vn/mbuyitem/callserviceapple?refresh_token=' . $refresh_token .
                    '&access_token=' . $access_token .
                    '&client_id=' . $client_id .
                    '&server_id=' . $server_id .
                    '&cpid=' . $cpid .
                    '&sub_cpid=' . $sub_cpid);

            return view('payment.mobile.step1_chose_app_item_ios', [
                'refresh_token' => $refresh_token,
                'access_token' => $access_token,
                'get_app_items' => $get_app_items,
                'topupcash_link' => $topupcash_link,
                'user_coins' => $user_coins,
                'callservice_link' => $callservice_link,
                'callserviceapple_link' => $callserviceapple_link,
                'itemlist' => $itemlist,
                'client_id' => $client_id,
                'inreview_status' => $inreview_status,
            ]);
        } else {
// view for android
            return view('payment.mobile.step1_chose_app_item', [
                'refresh_token' => $refresh_token,
                'access_token' => $access_token,
                'get_app_items' => $get_app_items,
                'topupcash_link' => $topupcash_link,
                'user_coins' => $user_coins,
                'client_id' => $client_id,
                'callservice_link' => $callservice_link,
            ]);
        }
    }

    public function getCallserviceapple(Request $request) {
// validate request
        $cpid = Input::has('cpid') ? Input::get('cpid') : '';    // optional
        $sub_cpid = Input::has('sub_cpid') ? Input::get('sub_cpid') : '';  // optional
        $product_apple_id = Input::get('product_apple_id');
        $client_id = Input::get('client_id');
        $server_id = Input::get('server_id');
        $access_token = Input::get('access_token');
        $refresh_token = Input::get('refresh_token');

        $validator = Validator::make($request->all(), [
                    'product_apple_id' => 'required',
                    'client_id' => 'required',
                    'server_id' => 'required',
                    'access_token' => 'required',
                    'refresh_token' => 'required',]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return Response::json($errors);
        }

// get list apple item buy client id
        $itemlist = MerchantAppProductApple::get_products_apple_by_client_id($client_id);
        $data = $this->search($itemlist, 'product_apple_id', $product_apple_id);
//dd($data);
// show error
        $error_code = 200;
        $message = 'Bạn vừa lựa chọn gói ' . $data->amount;


// redirect to this link, sdk will know result
        $url = $this->redirectToFinishUrlWithData($data, $error_code, $message, 'apple');
        return redirect($url);
    }

    public function getCallservice(Request $request) {
// validate request
        $cpid = Input::has('cpid') ? Input::get('cpid') : '';    // optional
        $sub_cpid = Input::has('sub_cpid') ? Input::get('sub_cpid') : '';  // optional
        $price = Input::get('price');
        $client_id = Input::get('client_id');
        $server_id = Input::get('server_id');
        $access_token = Input::get('access_token');
        $refresh_token = Input::get('refresh_token');

        $validator = Validator::make($request->all(), [
                    'price' => 'required',
                    'client_id' => 'required',
                    'server_id' => 'required',
                    'access_token' => 'required',
                    'refresh_token' => 'required',]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return Response::json($errors);
        }

        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $url = '';
        $uid = $user->id;
        $user_coins = CashInfo::getCoins();
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
// check coins
        if ($price > $user_coins) {
            $data = array();
            $error_code = NOT_ENOUGH_COINS;
            $message = 'Không đủ Coin';
            $url = $this->redirectToFinishUrlWithData($data, $error_code, $message);

            return redirect($url);
        }

        $trans_id = $this->createTransactionId($client_id, $uid);

// create service
        $service = GameServices::createService($client_id);

// transfer to game
        if ($service->transfer($user->id, $server_id, $trans_id, $price, $ip)) {
// update to log revenue

            if (empty($cpid)) {
                $detect = new Mobile_Detect();
                $os_id_default = 1;
                if ($detect->isiOS()) {
                    $os_id_default = 2;
                }

                if ($detect->isAndroidOS()) {
                    $os_id_default = 1;
                }

                $cpid = UtilHelper::getDefaultCpid($os_id_default, $client_id); // fix 1 - ios
            }
            $arr = [
                'cpid' => $cpid,
                'uid' => $uid,
                'telco' => "1",
                'clientid' => $client_id,
                'serial' => "1",
                'amount' => $price * 100,
                'code' => "1",
            ];
            $revenuelog = new RevenueLogHelper;
            $revenuelog->setRevenue($arr);

// reduce coin
            try {
                $status = CashInfo::decrementCoin($uid, $price);
                if ($status) {
                    $data = array('price' => $price);
                    $error_code = 200;
                    $message = 'Mua vật phẩm thành công';

// poupou log transaction to table core_log_coin_trans
//                    $arr_log['uid'] = $uid;
//                    $arr_log['server_id'] = $server_id;
//                    $arr_log['client_id'] = $client_id;
//                    $arr_log['trans_info'] = $trans_id;
//                    $arr_log['ip'] = $ip;
//                    $arr_log['trans_id'] = $trans_id;
//                    $arr_log['status'] = $status;
//                    $arr_log['money_in_game'] = $price;
//                    $arr_log['response_time'] = date('Y-m-d H:i:s');
//                    $arr_log['response'] = $request;
//                    $arr_log['type'] = 'fix';
//                    $arr_log['receipt'] = $request;
//                    $this->logCoinTransfer($arr_log);
                }
            } catch (Exception $e) {
                $data = array();
                $error_code = PAY_SYSTEM_ERROR;
                $message = $e->getMessage();
            }
        } else {
// show error
            $data = array('price' => $price);
            $error_code = FAIL_TRANSFER_COINS_TO_GAME;
            $message = 'Nạp tiền vào game thất bại, bạn vui lòng thử lại sau.';
        }

// redirect to this link, sdk will know result
        $url = $this->redirectToFinishUrlWithData($data, $error_code, $message);
        return redirect($url);
    }

    private function createTransactionId($client_id, $uid) {
        //return $client_id . $uid . time() . rand(1, 100);
        return $uid . time() . rand(1, 100);
    }

    public function getFinish() {
        $result = array();
        $result['data'] = json_decode(urldecode(Input::get('data')));
        $result['error_code'] = Input::get('error_code');
        $access_token = 0;
        $result['message'] = Input::get('message');
        if ($result['error_code'] == 200) {
            return view('payment.mobile.step3_success', ['access_token' => $access_token, 'message' => $result['message']]);
        } else {
            return view('payment.mobile.step3_fail', ['access_token' => $access_token, 'message' => $result['message']]);
        }
        return Response::json($result);
    }

    public function getApple() {
        $result = array();
        $result['data'] = json_decode(urldecode(Input::get('data')));
        $result['error_code'] = Input::get('error_code');
        $access_token = 0;
        $result['message'] = Input::get('message');
        if ($result['error_code'] == 200) {
            return view('payment.mobile.step3_success', ['access_token' => $access_token, 'message' => $result['message']]);
        } else {
            return view('payment.mobile.step3_fail', ['access_token' => $access_token, 'message' => $result['message']]);
        }
        return Response::json($result);
    }

    private function isMobile() {
        if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
            return true;
        else
            return false;
    }

    private function redirectToFinishUrlWithData($data, $error_code, $message, $type = 0) {
        $data = urlencode(json_encode(array($data)));
        $error_code = $error_code;
        $message = $message;
        $url = '//pay.slg.vn/mbuyitem/finish?data=' . $data . '&error_code=' . $error_code . '&message=' . $message;
        if ($type == 'apple') {
            $url = '//pay.slg.vn/mbuyitem/apple?data=' . $data . '&error_code=' . $error_code . '&message=' . $message;
        }
        return $url;
    }

    public function getTest(Request $request) {
        $user = Auth::user();

        $uid = $user->id;
        $client_id = '2044387389';
        $server_id = 1;
        $price = 100;
        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }
        //dd('test');
        $trans_id = $this->createTransactionId($client_id, $uid);
        // create service
        $service = GameServices::createService($client_id);
        // transfer to game
        if ($service->transfer($user->id, $server_id, $trans_id, $price, $ip)) {
            
        }
//        return Response::json($data_response);
    }

    public function checkIosDevice() {
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");

        if ($iPad || $iPhone || $iPad)
            return TRUE;

        return FALSE;
    }

    function search($array, $key, $value) {
        $item = null;
        foreach ($array as $struct) {
            if ($value == $struct->$key) {
                $item = $struct;
                break;
            }
        }
        return $item;
    }

    private function logCoinTransfer($arr) {
        $app = MerchantApp::where('clientid', $arr['client_id'])->first();

        $log = new LogCoinTransfer;
//$log->request_time = $arr['request_time'];
        $log->user_id = $arr['uid'];
        $log->server_id = $arr['server_id'];
        $log->app_id = $app ? $app->id : $arr['client_id'];
        $log->trans_info = $arr['trans_info'];
        $log->ip = $arr['ip'];
        $log->trans_id = $arr['trans_id'];
        $log->status = $arr['status'];
        $log->coin = $arr['money_in_game'];
        $log->request_time = $arr['response_time'];
        $log->response_time = $arr['response_time'];
        $log->response = json_encode($arr['response']);
        $log->type = $arr['type'];
        $log->receipt = json_encode($arr['receipt']);
        $log->save();
    }

}

?>