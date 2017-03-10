<?php

namespace App\Http\Controllers\Payment;

use DB,
    cURL,
    Log;
use Cache,
    Exception;
use Validator;
use Session;
use App\Models\CardTest;
use App\Models\CashInfo;
use App\Models\LogChargeTelco;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\LogController;
use App\Http\Controllers\Payment\Partner\AtmCardBanknet;
use App\Http\Controllers\Payment\Partner\VisaMasterBanknet;
use App\Helpers\Payments\PayDirect;
use App\Helpers\Payments\CyberPay;
use App\Helpers\Payments\NganLuong;
use App\Helpers\Payments\NganLuongATM;
use App\Helpers\CommonHelper;
use App\Helpers\Logs\TopupLogHelper;

class TopupController extends Controller
{

    private static $moneys = [50000, 100000, 200000, 300000, 500000, 1000000, 2000000, 3000000, 5000000, 10000000];
    private static $moneys_test = [10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000, 1000000, 2000000, 3000000, 5000000, 10000000];
    private static $testers = ['tieumai93', 'caovuong', 'tomhager'];
    private static $black_list = ['memory3t', 'nhoxzz112'];

    public function __construct()
    {
        $this->middleware('csrf', ['only' => ['postTelco', 'postBank', 'postNganLuong']]);
//        $this->middleware('log-request', ['only' => ['getAtmcallback', 'getVisacallback']]);
    }

    public function getPromotion($coin)
    {
//        $date = date('d-m');
//
//        if (in_array($date, ['14-02', '15-02', '16-02'])) {
//            $rate = ($coin < 30000) ? 0.05 : 0.1;
//
//            $bonus = $coin * $rate;
//
//            return (int) ($coin + $bonus);
//        }

        return $coin;
    }

    public function missingMethod($parameters = array())
    {

        return view('errors.404');
    }

    private function getMoney($username)
    {
        return in_array($username, self::$testers) ? self::$moneys_test : self::$moneys;
    }

    private function isBlocked($username)
    {
        return in_array($username, self::$black_list) ? true : false;
    }

    public function getIndex()
    {
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }
        return view('frontend.topup-step1');
    }

    public function getTelco(Request $request)
    {
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

        return view('frontend.topup-step2-telco', compact('scratch_card_paygate', 'cyberpay'));
    }

    public function postTelco(Request $request)
    {
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

//        if (!$request->has('g-recaptcha-response')) {
//            $request->session()->flash('flash_error', 'Vui lòng xác thực bạn là con người, không phải máy.');
//            return redirect()->back()->withInput();
//        }

        $ip = CommonHelper::getClientIP();

//        try {
//            $verify = cURL::post('https://www.google.com/recaptcha/api/siteverify', [
//                        'secret' => '6LfDGBMTAAAAAILSMorVcznkh8XiCX6AMuuerUzQ',
//                        'response' => $request->input('g-recaptcha-response'),
//                        'remoteip' => $ip,
//            ]);
//        } catch (\Exception $e) {
//            Log::debug($e->getMessage());
//            $request->session()->flash('flash_error', 'Lỗi khi gọi chứng thực Captcha, vui lòng thử lại.');
//            return redirect()->back()->withInput();
//        }
//
//        $captcha_response = json_decode($verify->body, true);
//
//        if (!isset($captcha_response['success']) || $captcha_response['success'] === false) {
//            $request->session()->flash('flash_error', 'Vui lòng xác thực bạn là con người, không phải máy.');
//            return redirect()->back()->withInput();
//        }

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

        if (starts_with($card_code, 'test_')) {
            return CardTest::charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator);
        } else {
            if ($card_type == 'CYBERPAY') { //CyperPay là loại thẻ riêng, NganLuong và PayDirect là cổng thanh toán gồm nhiều loại thẻ
                $instance = CyberPay::class;
            } else {
                if ($_paygate == 'PayDirect') {
                    $instance = PayDirect::class;
                } elseif ($_paygate == 'NganLuong') {
                    $instance = new NganLuong($uid);
                    return $instance->charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed);
                }
            }

            return $instance::charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed);
        }
    }

    public function getNganLuong()
    {
        if (Cache::get('settings_paygate_nganluong', 0) == 0) {
            return redirect()->route('topupcash.index');
        }

        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $moneys = $this->getMoney($user->name);

        return view('frontend.topup-step2-nl', compact('moneys'));
    }

    public function postNganLuong(Request $request)
    {
        if (Cache::get('settings_paygate_nganluong', 0) == 0) {
            return redirect()->route('topupcash.index');
        }

        $user = Auth::user();

        $moneys = $this->getMoney($user->name);

        $this->validate($request, [
            'money' => 'numeric|in:' . implode(',', $moneys),
        ], [], [
            'money' => 'Số tiền',
        ]);

        $user = Auth::user();

        $transid = "SML_" . $user->id . '_' . time() . '_' . rand();

        $amount = $request->input('money');

        $url = NganLuongATM::buildCheckoutUrl($transid, $request->input('money'), "Nap $amount VND vao tai khoan {$user->name} ($user->id) tren SLG.");

        $arr_log = ['uid' => $user->id, 'amount' => $amount, 'card_type' => 'NganLuong', 'partner_type' => 'NganLuongATM', 'trans_id' => $transid];
        LogController::logChargeCard($arr_log);

        return redirect()->to($url);
    }

    public function getNganLuongCallback(Request $request)
    {
        if ($request->has('order_code')) {
            $transid = $request->input('order_code');

            $order = LogChargeTelco::getLogChargeCardByTransid($transid);
            if ($order !== false) {
                $amount = $order->amount;

                $transaction_info = $request->input('transaction_info');
                $payment_id = $request->input('payment_id');
                $payment_type = $request->input('payment_type');
                $error_text = $request->input('error_text');
                $secure_code = $request->input('secure_code');

                $success = NganLuongATM::verifyPaymentUrl($transaction_info, $transid, $amount, $payment_id, $payment_type, $error_text, $secure_code);

                if ($success) {
                    if ($error_text === '') {
                        $uid = $order->uid;
                        $coin = $amount / 100;

                        $coin = $this->getPromotion($coin);

                        $added = CashInfo::incrementCoin($uid, $coin);

                        if ($added) {
                            $order->coin = $coin;
                            $order->payment_status = 'success';
                            $order->response = json_encode($request->all());
                            $order->save();

                            $arr = [
                                'cpid' => 'NganLuongATM',
                                'uid' => $uid,
                                'telco' => $payment_type,
                                'clientid' => $request->input('client_id'),
                                'serial' => '',
                                'amount' => $amount,
                                'device_id' => $request->input('device_id'),
                                'os_id' => '',
                                'code' => $secure_code,
                                'response' => 200,
                            ];

                            $revenuelog = new TopupLogHelper;
                            $revenuelog->setTopup($arr);

                            $request->session()->flash('flash_success', 'Bạn vừa nạp thành công ' . $coin . ' Coin.');
                        } else {
                            Log::alert("Lỗi Topup qua Ngân Lượng ATM: Không thể nạp thêm Coin. | Request: " . json_encode($request->all()));

                            $request->session()->flash('flash_error', 'Có lỗi khi xử lý giao dịch. Vui lòng liên hệ với chúng tôi.');
                        }
                    } else {
                        Log::alert("Lỗi Topup qua Ngân Lượng ATM: $error_text | Request: " . json_encode($request->all()));

                        $request->session()->flash('flash_error', 'Có lỗi khi xử lý giao dịch. Vui lòng thử lại.');
                    }
                } else {
                    $request->session()->flash('flash_error', 'Giao dịch không hợp lệ. Vui lòng thử lại.');
                }
            }
        }

        return redirect()->route('topupcash.get.nl');
    }

    public function getBank()
    {
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

        $moneys = $this->getMoney($user->name);

        return view('frontend.topup-step2-bank', compact('moneys'));
    }

    public function postBank(Request $request)
    {
        $user = Auth::user();
        if (!isset($user->id) && empty($user->id)) {
            return view('errors.404');
        }

//        if (!$request->has('g-recaptcha-response')) {
//            $request->session()->flash('flash_error', 'Vui lòng xác thực bạn là con người, không phải máy.');
//            return redirect()->back()->withInput();
//        }

//        $ip = CommonHelper::getClientIP();

//        try {
//            $verify = cURL::post('https://www.google.com/recaptcha/api/siteverify', [
//                        'secret' => '6LfDGBMTAAAAAILSMorVcznkh8XiCX6AMuuerUzQ',
//                        'response' => $request->input('g-recaptcha-response'),
//                        'remoteip' => $ip,
//            ]);
//        } catch (\Exception $e) {
//            $request->session()->flash('flash_error', 'Lỗi khi gọi chứng thực Captcha, vui lòng thử lại.');
//            return redirect()->back()->withInput();
//        }
//        $captcha_response = json_decode($verify->body, true);
//
//        if (!isset($captcha_response['success']) || $captcha_response['success'] === false) {
//            $request->session()->flash('flash_error', 'Vui lòng xác thực bạn là con người, không phải máy.');
//            return redirect()->back()->withInput();
//        }

        $moneys = $this->getMoney($user->name);

        $types = [
            'atm'
        ];

//        if ($user->verified_payment) {
        $types[] = 'visa';
//        }

        // validate request
        $validator = Validator::make($request->all(), [
            'card_type' => 'required|in:' . implode(',', $types),
            'money' => 'required|in:' . implode(',', $moneys),
            'game' => 'required'
        ]);
        // check token
        $token_form = Input::get('_token');
        $token_sess = \Session::token();

        if ($token_form != $token_sess) {
            return view('errors.404');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $card_type = Input::get('card_type');

        $is_blocked = $this->isBlocked($user->name);

        if ($is_blocked)
        {
            $card_type = 'atm';
        }

        $money = Input::get('money');

        $time = time();

        $merchantTxnRef = "SML_" . $user->id . '_' . $time . '_' . rand();

        $arr = array(
            'vpc_Amount' => (string)$money,
            'vpc_MerchTxnRef' => $merchantTxnRef,
            'game' => $request->input('game'),
        );

        // log
        $arr_log = ['uid' => $user->id, 'amount' => $money, 'card_type' => $card_type, 'partner_type' => 'Banknet', 'trans_id' => $merchantTxnRef];
        LogController::logChargeCard($arr_log);


        if ($card_type == 'atm') {
            AtmCardBanknet::redirectToBanknet($arr);
        } else if ($card_type == 'visa') {
            VisaMasterBanknet::redirectToBanknet($arr);
        } else {
            dd($request);
        }
        return Response::json(PAY_SYSTEM_ERROR);
    }

    public function getBankcallback(Request $request)
    {
        dd($request);
        return;
    }

    public function getSuccess()
    {
        return view('payment.web.step3_success');
    }

    public function getFail()
    {
        return view('payment.web.step3_fail');
    }

    public function getAtmcallback(Request $request)
    {
        $vpc_AcqResponseCode = Input::get('vpc_TxnResponseCode');
        $vpc_Amount = Input::get('vpc_Amount');
        $vpc_MerchTxnRef = Input::get('vpc_MerchTxnRef');

        try {
            // check order status from banknet
            $result = AtmCardBanknet::checkAtmCardBanknetTransaction($vpc_MerchTxnRef);
        } catch (Exception $e) {
            Log::alert('Lỗi gọi ATM: ' . $e->getMessage() . ' | Request: ' . json_encode($request));
        }


//        for ($i = 0; $i < 2; $i++) {
//            // check order status from banknet
//            $result = AtmCardBanknet::checkAtmCardBanknetTransaction($vpc_MerchTxnRef);
//            if (is_object($result) && isset($result->vpc_TxnResponseCode) && ($result->vpc_TxnResponseCode == '00' || $result->vpc_TxnResponseCode == '0' )) {
//                break;
//            }
//            sleep(1);
//        }

        if (is_object($result) && isset($result->vpc_TxnResponseCode) && ($result->vpc_TxnResponseCode == '00' || $result->vpc_TxnResponseCode == '0')) {

            // nap thanh cong
            $order = LogChargeTelco::getLogChargeCardByTransid($vpc_MerchTxnRef);
            if (is_object($order) && isset($order->uid) && isset($order->amount) && $order->amount > 0) {
                $amount = $order->amount;
                $uid = $order->uid;
                $coin = $amount / 100; // 10k vnd => 100 coin

                $coin = $this->getPromotion($coin);

                // update coin vao vi
                $update_coin_status = CashInfo::incrementCoin($uid, $coin);

                // set msg to step 3
                $msg = 'Nạp thẻ thành công.';

                //update log
                if ($update_coin_status) {
                    $order->coin = $coin;
                    $order->payment_status = 'success';
                    $order->response = json_encode($request->all());
                    $order->save();
                    $request->session()->flash('flash_success', $msg);
                    return redirect()->route('topupcash.bank');
                }
            }
        }

        $request->session()->flash('flash_error', 'Giao dịch không thành công.');

        return redirect()->route('topupcash.bank');
    }

    public function getVisacallback(Request $request)
    {

        $vpc_MerchTxnRef = Input::get('vpc_MerchTxnRef');
        $vpc_TxnResponseCode = Input::has('vpc_TxnResponseCode') ? Input::get('vpc_TxnResponseCode') : 7;
        $securesecret = '4794E37133C78EFD3A5D3C19C57F6D91';
        $vpc_Txn_Secure_Hash = strtoupper($_GET['vpc_SecureHash']);
        unset($_GET['vpc_SecureHash']);
        unset($_GET['vpc_SecureHashType']);
        if ($vpc_TxnResponseCode != "7" && $vpc_TxnResponseCode != "No Value Returned") {

            $hashinput = '';

            // sort all the incoming vpc response fields and leave out any with no value

            ksort($_GET);

            foreach ($_GET as $key => $value) {
                if ((strlen($value) > 0) && (starts_with($key, 'vpc_') || starts_with($key, 'user_'))) {
                    $hashinput .= $key . "=" . $value . "&";
                }
            }

            $hashinput = rtrim($hashinput, "&");

            $hashed = strtoupper(hash_hmac('SHA256', $hashinput, pack('H*', $securesecret)));

            if ($vpc_Txn_Secure_Hash == $hashed) {
                $hashValidated = TRUE;
            } else {
                $hashValidated = FALSE;
                Log::debug('$vpc_Txn_Secure_Hash:' . $vpc_Txn_Secure_Hash . ' - $hashed:' . $hashed);
            }
        } else {
            // Secure Hash was not validated, add a data field to be displayed later.
            $hashValidated = $hashValidated = FALSE;
        }

        if ($hashValidated && $vpc_TxnResponseCode == '0') {

            // nap thanh cong
            $order = LogChargeTelco::getLogChargeCardByTransid($vpc_MerchTxnRef);

            if (is_object($order) && isset($order->uid) && isset($order->amount) && $order->amount > 0) {
                $amount = $order->amount;
                $uid = $order->uid;
                $coin = $amount / 100; // 10k vnd => 100 coin

                $coin = $this->getPromotion($coin);

                // update coin vao vi
                $update_coin_status = CashInfo::incrementCoin($uid, $coin);

                // set msg to step 3
                $msg = 'Nạp thẻ thành công.';

                //update log
                if ($update_coin_status) {
                    $order->coin = $coin;
                    $order->payment_status = 'success';
                    $order->response = json_encode($request->all());
                    $order->save();
                    $request->session()->flash('flash_success', $msg);
                    return redirect()->route('topupcash.bank');
                }
            }
        }

        $request->session()->flash('flash_error', 'Giao dịch không thành công.');

        return redirect()->route('topupcash.bank');
    }

}
