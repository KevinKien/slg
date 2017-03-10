<?php

namespace App\Http\Controllers\Payment;

use DB,
    cURL,
    Log,
    Exception,
    Cache;
use Validator,
    App\Models\CardTest;
use App\Models\CashInfo;
use App\Models\LogChargeTelco;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\LogController;
use App\Http\Controllers\Payment\Partner\AtmCardBanknet;
use App\Http\Controllers\Payment\Partner\VisaMasterBanknet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Session,
    Detection\MobileDetect;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\Helpers\Payments\PayDirect;
use App\Helpers\CommonHelper;
use App\Helpers\Payments\CyberPay;
use App\Helpers\Payments\NganLuong;
use App\Helpers\Payments\Mwork;
use App\Helpers\Payments\Gmod;


class TopupMobileController extends Controller {

    private static $moneys = [50000, 100000, 200000, 300000, 500000, 1000000, 2000000, 3000000, 5000000];

    public function __construct(Request $request) {
        $this->middleware('oauth', ['only' => ['get_index', 'mtopupcash/telco', 'postBank']]);
        $this->middleware('csrf', ['only' => ['postTelco', 'postBank']]);
    }

    public function getList() {
        return CashInfo::all();
    }

    public function missingMethod($parameters = array()) {

        return view('errors.404');
    }

    public function get_index() {
        $params = [
            'access_token' => Input::get('access_token'),
            'client_id' => Input::get('client_id'),
            'cpid' => Input::get('cpid'),
        ];

        $resourceOwnerType = Authorizer::getResourceOwnerType();
        $resourceOwnerId = Authorizer::getResourceOwnerId();

        if ($resourceOwnerType === 'user') {
            $user = Auth::loginUsingId($resourceOwnerId);
        }

        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }
        return view('payment.mobile.step1_chose_payment_method', ['params' => $params]);
    }

    public function getTelco(Request $request) {
        $access_token = $request->input('access_token');

        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $session_failed = $user->id . '_topup_failed';
        $session_exp = $user->id . '_topup_expire';

        $expire = $request->session()->get($session_exp, 0);

        if ($expire > 0 && $expire < time()) {
            $request->session()->forget($session_failed);
            $request->session()->forget($session_exp);
        }

        $failed = $request->session()->get($session_failed, 0);

        if ($expire >= time() && $failed > 2) {
            $request->session()->flash('flash_error', 'Bạn đã nạp thẻ thất bại 3 lần liên tiếp, vui lòng thử lại sau 5 phút.');
        }

        $scratch_card_paygate = Cache::get('settings_scratch_card_paygate', 'NganLuong');
        $cyberpay = Cache::get('settings_scratch_card_paygate_cyberpay', 1);

        return view('payment.mobile.step2_telco', compact('scratch_card_paygate', 'cyberpay', 'access_token'));
    }

    public function postTelco(Request $request) {
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $session_failed = $user->id . '_topup_failed';
        $session_exp = $user->id . '_topup_expire';

        $expire = $request->session()->get($session_exp, 0);

        if ($expire > 0 && $expire < time()) {
            $request->session()->forget($session_failed);
            $request->session()->forget($session_exp);
        }

        $failed = $request->session()->get($session_failed, 0);

        if ($expire >= time() && $failed > 2) {
            $request->session()->flash('flash_error', 'Bạn đã nạp thẻ thất bại 3 lần liên tiếp, vui lòng thử lại sau 5 phút.');
            return redirect()->back()->withInput();
        }

        $card_types = [
            'MOBI',
            'VINA',
            'VT',
        ];

        $_paygate = Cache::get('settings_scratch_card_paygate', 'NganLuong');
        $cyberpay = Cache::get('settings_scratch_card_paygate_cyberpay', 1);

//        if ($_paygate == 'PayDirect') {
        $card_types[] = 'GATE';
//        }

        if ($cyberpay == 1) {
            $card_types[] = 'CYBERPAY';
        }

        $rules = [
            'card_type' => 'required|in:' . join(',', $card_types),
            'card_code' => 'required|numeric|digits_between:8,15',
            'card_seri' => 'required|min:8|max:15',
            'order_mobile' => 'required|numeric|digits_between:10,11',
        ];

        $card_type = $request->input('card_type');

        if ($card_type == 'MOBI') {
            $rules['card_code'] = 'required|numeric|digits_between:12,14';
            $rules['card_seri'] = 'required|min:9|max:15';
        } elseif ($card_type == 'VINA') {
            $rules['card_code'] = 'required|numeric|digits_between:12,14';
            $rules['card_seri'] = 'required|min:8|max:15';
        } elseif ($card_type == 'VT') {
            $rules['card_code'] = 'required|numeric|digits_between:13,15';
            $rules['card_seri'] = 'required|min:11|max:15';
        } elseif ($card_type == 'CYBERPAY') {
            $rules['card_code'] = 'required|numeric|digits:12';
            $rules['card_seri'] = 'required|numeric|digits:12';
        }

        $card_code = $request->input('card_code');

        if (starts_with($card_code, 'test_')) {
            $rules['card_code'] = 'required';
            $rules['card_seri'] = 'required';
        }

        // validate request
        $validator = Validator::make($request->all(), $rules);

        $validator->setAttributeNames([
            'card_type' => 'Loại thẻ',
            'card_code' => 'Mã thẻ',
            'card_seri' => 'Số Serial',
            'order_mobile' => 'Số điện thoại',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $uid = $user->id;
        $transid = '00711' . '_' . $uid . '_' . uniqid();

        $card_seri = $request->input('card_seri');
        $order_mobile = $request->input('order_mobile');

        $ip = CommonHelper::getClientIP();

        $detect = new MobileDetect;

        $os_id = 0;

        if ($detect->isiOS()) {
            $os_id = 2;
        } elseif ($detect->isAndroidOS()) {
            $os_id = 1;
        }

        if (starts_with($card_code, 'test_')) {
            return CardTest::charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator);
        } else {
            $instance = NULL;
            $provider = !empty($user->provider) ? strtolower($user->provider) : 'slg';
            switch ($provider) {
                case 'mwork':
                    $uid = $user;
                    $instance = Mwork::class;
                    break;
                case 'gmob':
                    $uid = $user;
                    $instance = Gmod::class;
                    break;
            }

            if (empty($instance)) {
                if ($card_type == 'CYBERPAY') { //CyperPay là loại thẻ riêng, NganLuong và PayDirect là cổng thanh toán gồm nhiều loại thẻ
                    $instance = CyberPay::class;
                } else {
                    if ($_paygate == 'PayDirect') {
                        $instance = PayDirect::class;
                    } elseif ($_paygate == 'NganLuong') {
                        $instance = new NganLuong($uid);
                        return $instance->charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed, $os_id);
                    }
                }
            }

            return $instance::charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed, $os_id);
        }
    }

    public function getBank() {
        $access_token = Input::get('access_token');
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        return view('payment.mobile.step2_bank', ['access_token' => $access_token, 'moneys' => self::$moneys]);
    }

    public function postBank(Request $request) {
        $access_token = Input::get('access_token');

        $resourceOwnerType = Authorizer::getResourceOwnerType();
        $resourceOwnerId = Authorizer::getResourceOwnerId();

        if ($resourceOwnerType === 'user') {
            $user = Auth::loginUsingId($resourceOwnerId);
        }

        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $types = [
            'atm',
            'visa'
        ];

        // validate request
        $validator = Validator::make($request->all(), [
                    'card_type' => 'required|in:' . implode(',', $types),
                    'money' => 'required|in:' . implode(',', self::$moneys),
                    'game' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $card_type = Input::get('card_type');
        $money = Input::get('money');

        $time = time();
        $url = '';

        $merchantTxnRef = "SML_" . $user->id . '_' . $time . '_' . rand();

        $arr = array(
            'vpc_Amount' => (string) $money,
            'vpc_MerchTxnRef' => $merchantTxnRef,
            'game' => $request->input('game'),
        );

        // log
        $arr_log = ['uid' => $user->id, 'amount' => $money, 'card_type' => $card_type, 'partner_type' => 'Banknet', 'trans_id' => $merchantTxnRef, 'access_token' => $access_token];
        $log = LogController::logChargeCard($arr_log);

        $is_mobile = TRUE;
        if ($card_type == 'atm') {
            $url = AtmCardBanknet::redirectToBanknet($arr, $is_mobile);
        } else if ($card_type == 'visa') {
            $url = VisaMasterBanknet::redirectToBanknet($arr, $is_mobile);
        } else {
            dd($request);
        }
        return Response::json(PAY_SYSTEM_ERROR);
    }

    public function getBankcallback(Request $request) {
        dd($request);
        return;
    }

    public function getSuccess() {
        $access_token = Input::get('access_token');
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }
        return view('payment.mobile.step3_success', ['access_token' => $access_token]);
    }

    public function getFail() {
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }
        return view('payment.mobile.step3_fail');
    }

    public function getAtmcallback(Request $request) {
        $access_token = '';
        $vpc_AcqResponseCode = Input::get('vpc_TxnResponseCode');
        $vpc_Amount = Input::get('vpc_Amount');
        $vpc_MerchTxnRef = Input::get('vpc_MerchTxnRef');
        $access_token = Input::has('access_token');

        // check order status from banknet
        $result = AtmCardBanknet::checkAtmCardBanknetTransaction($vpc_MerchTxnRef);
        if (is_object($result) && isset($result->vpc_TxnResponseCode) && $result->vpc_TxnResponseCode == '00') {
            // nap thanh cong
            $order = LogChargeTelco::getLogChargeCardByTransid($vpc_MerchTxnRef);

            if (is_object($order) && isset($order->uid) && isset($order->amount) && $order->amount > 0) {
                $amount = $order->amount;
                $uid = $order->uid;
                $access_token = $order->access_token;
                $coin = $amount / 100; // 10k vnd => 100 coin
                // update coin vao vi
                $update_coin_status = CashInfo::incrementCoin($uid, $coin);

                // set msg to step 3
                $msg = 'Nạp thẻ thành công.';
                $user_login = Auth::loginUsingId($uid);

                //update log
                if ($update_coin_status) {
                    $order->coin = $coin;
                    $order->payment_status = 'success';
                    $order->response = json_encode($request->all());
                    $order->save();
                    return view('payment.mobile.step3_success', ['access_token' => $access_token]);
                }
            }
        }
        return view('payment.mobile.step3_fail', ['access_token' => $access_token, 'message' => 'Giao dịch không thành công.']);
    }

    public function getVisacallback(Request $request) {
        $access_token = '';
        $vpc_AcqResponseCode = Input::has('vpc_TxnResponseCode') ? Input::get('vpc_TxnResponseCode') : 7;
        $vpc_Amount = Input::get('vpc_Amount');
        $vpc_MerchTxnRef = Input::get('vpc_MerchTxnRef');
        $access_token = Input::has('access_token');

        $securesecret = '4794E37133C78EFD3A5D3C19C57F6D91';
        $vpc_Txn_Secure_Hash = strtoupper($_GET['vpc_SecureHash']);
        unset($_GET['vpc_SecureHashType']);
        unset($_GET['vpc_SecureHash']);
        $errorExists = false;

        if (strlen($securesecret) > 0 && $vpc_AcqResponseCode != "7" && $vpc_AcqResponseCode != "No Value Returned") {

            $hashinput = '';

            // sort all the incoming vpc response fields and leave out any with no value

            ksort($_GET);

            foreach ($_GET as $key => $value) {
                if ((strlen($value) > 0) && (starts_with($key, 'vpc_') || starts_with($key, 'user_'))) {
                    $hashinput .= $key . "=" . $value . "&";
                }
            }

            $hashinput = rtrim($hashinput, "&");

            $hashed = strtoupper(hash_hmac('SHA256', $hashinput, pack('H*',$securesecret)));

            if ($vpc_Txn_Secure_Hash == $hashed) {
                $hashValidated = TRUE;
            } else {
                $hashValidated = FALSE;
            }
        } else {
            // Secure Hash was not validated, add a data field to be displayed later.
            $hashValidated = $hashValidated = FALSE;
        }

        // check order status from banknet
        //$result = VisaMasterBanknet::checkVisaCardBanknetTransaction($vpc_MerchTxnRef);

        if ($hashValidated && Input::has('vpc_AcqResponseCode') && Input::get('vpc_AcqResponseCode') == '00') {
            // nap thanh cong
            $order = LogChargeTelco::getLogChargeCardByTransid($vpc_MerchTxnRef);

            //dd($vpc_MerchTxnRef);
            if (is_object($order) && isset($order->uid) && isset($order->amount) && $order->amount > 0) {
                $amount = $order->amount;
                $uid = $order->uid;
                $access_token = $order->access_token;
                $coin = $amount / 100; // 10k vnd => 100 coin
                // update coin vao vi
                $update_coin_status = CashInfo::incrementCoin($uid, $coin);

                // set msg to step 3
                $msg = 'Nạp thẻ thành công.';
                $user_login = Auth::loginUsingId($uid);

                //update log
                if ($update_coin_status) {
                    $order->coin = $coin;
                    $order->payment_status = 'success';
                    $order->response = json_encode($request->all());
                    $order->save();
                    return view('payment.mobile.step3_success', ['access_token' => $access_token]);
                }
            }
        }
        return view('payment.mobile.step3_fail', ['access_token' => $access_token, 'message' => 'Giao dịch không thành công.']);
    }

    private function isMobile() {
        if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
            return true;
        else
            return false;
    }

    public function getTest() {
        if (Auth::check()) {
            $rs = '00';
            $user = Auth::user();
            $provider = !empty($user->provider) ? strtolower($user->provider) : 'slg';
            switch ($provider) {
                case 'mwork':
                    $instance = Mwork::class;
                    $uid = 5107755;
                    $card_code = '917259759036';
                    $card_seri = '048071000007476';
                    $card_type = 'MOBI';
                    $transid = rand(1000000, 999999999999999);
                    $rs = $instance::charge1($uid, $card_code, $card_seri, $card_type, $transid);
                    break;
                default :
                    echo 'call payment slg';
            }
            //output result
            dd($rs);
        }
    }

}

?>