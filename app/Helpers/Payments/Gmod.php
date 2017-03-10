<?php

namespace App\Helpers\Payments;

use cURL,
    App\Http\Controllers\Util\LogController,
    App\Models\CashInfo;
use Log,
    App\Helpers\Logs\TopupLogHelper;
use Artisaninweb\SoapWrapper\Facades\SoapWrapper;

class Gmod {

    public static $merchant_id = '45895';
    public static $merchant_account = 'huett@vinhxuan.com.vn';
    public static $password = '37e46cd6ee2d408eebc0959b461f848a';
    public static $version = '2.0';
    public static $url = 'https://www.nganluong.vn/mobile_card.api.post.v2.php';
    public static $bank_url = 'https://www.nganluong.vn/checkout.php';
    public $callback_url;

    public static function getMessage($code) {
        switch ($code) {
            default:
                return '';
            case '00':
                return 'Giao dịch thành công.';
            case '01':
                return 'Sai địa chỉ IP.';
            case '02':
                return 'Tham số thiếu hoặc không hợp lệ.';
            case '03':
                return 'Merchant không tồn tại hoặc Merchant đang bị khóa.';
            case '04':
                return 'Checksum không chính xác.';
            case '05':
                return 'Tài khoản nhận tiền nạp của Merchant không tồn tại.';
            case '06':
                return 'Tài khoản nhận tiền nạp của Merchant đang bị khóa hoặc bị phong tỏa, không thể thực hiện được giao dịch nạp tiền.';
            case '07':
                return 'Thẻ đã được sử dụng.';
            case '08':
                return 'Thẻ bị khóa.';
            case '09':
                return 'Thẻ hết hạn sử dụng.';
            case '10':
                return 'Thẻ chưa được kích hoạt hoặc không tồn tại.';
            case '11':
                return 'Mã thẻ sai định dạng.';
            case '12':
                return 'Sai số Serial của thẻ.';
            case '13':
                return 'Mã thẻ và số serial không khớp.';
            case '14':
                return 'Thẻ không tồn tại.';
            case '15':
                return 'Thẻ không sử dụng được.';
            case '16':
                return 'Số lần thử (nhập sai liên tiếp) của thẻ vượt quá giới hạn cho phép.';
            case '17':
                return 'Hệ thống bị lỗi hoặc quá tải, thẻ chưa bị trừ.';
            case '18':
                return 'Hệ thống bị lỗi hoặc quá tải, thẻ có thể đã bị trừ, cần tra soát lại.';
            case '19':
                return 'Kết nối bị lỗi, thẻ chưa bị trừ.';
            case '20':
                return 'Kết nối tới telco thành công, thẻ bị trừ nhưng chưa cộng tiền.';
            case '99':
                return 'Lỗi không xác định khi xử lý giao dịch.';
        }
    }

    //Hàm xây dựng url, trong đó có tham số mã hóa (còn gọi là public key)
    public function buildCheckoutUrl($order_code, $price, $transaction_info) {
        // Mảng các tham số chuyển tới nganluong.vn
        $arr_param = [
            'merchant_site_code' => strval(static::$merchant_id),
            'return_url' => strtolower(urlencode($this->callback_url)),
            'receiver' => strval(static::$merchant_account),
            'transaction_info' => strval($transaction_info),
            'order_code' => strval($order_code),
            'price' => strval($price)
        ];

        $secure_code = implode(' ', $arr_param) . ' ' . static::$password;
        $arr_param['secure_code'] = md5($secure_code);

        /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào */
        $redirect_url = static::$bank_url;
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?';
        } else if (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false) {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }

        /* Bước 3. tạo url */
        $url = '';
        foreach ($arr_param as $key => $value) {
            if ($key != 'return_url')
                $value = urlencode($value);

            if ($url == '')
                $url .= $key . '=' . $value;
            else
                $url .= '&' . $key . '=' . $value;
        }

        return $redirect_url . $url;
    }

    /* Hàm thực hiện xác minh tính đúng đắn của các tham số trả về từ nganluong.vn */

    public function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code) {
        // Tạo mã xác thực từ chủ web
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($payment_id);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($error_text);
        $str .= ' ' . strval(static::$merchant_id);
        $str .= ' ' . strval(static::$password);

        // Mã hóa các tham số
        $verify_secure_code = md5($str);

        // Xác thực mã của chủ web với mã trả về từ nganluong.vn
        if ($verify_secure_code === $secure_code)
            return true;

        return false;
    }

    public static function charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed, $os_id = '') {
        $sub_cpid = 'tuha';
        if (is_object($uid)) {
            $user = $uid;
            $uid = $user->id;
            $sub_cpid = !empty($user->sub_cpid) ? $user->sub_cpid : 'tuha';
            $username = $user->name;
        }
        $telco1 = '';
        $url = '';
        $fields = array();
        $app_id = !empty($app_id) ? $app_id : '18903334';

        $headers = array(
            'Content-Type: application/json',
        );

        $fields = array(
            'apikey' => '61fa0ebcfaf1695888caed1c215b7231',
            'pin' => $card_code,
            'serial' => $card_seri,
            'type' => $card_type,
            'app_code' => 'tieulong',
            'refcode' => $sub_cpid,
            'provider' => '0',
        );
        $params = http_build_query($fields);

        $nl_card_type = [
            'MOBI' => 'VMS',
            'VINA' => 'VNP',
            'VT' => 'VTT',
            'GATE' => 'FPT',
        ];

        $payload = $_payload = [
            'func' => 'CardCharge',
            'app_id' => $app_id,
            'pin_card' => $card_code,
            'card_serial' => $card_seri,
            'type_card' => $nl_card_type[$card_type],
            'ref_code' => $transid,
        ];

        $payload['merchant_id'] = null;
        $payload['merchant_account'] = null;


        // Add a new service to the wrapper
        SoapWrapper::add(function ($service) {
            $service
                    ->name('currency')
                    ->wsdl('http://gmob.vn/service/ReceiveCard.php?wsd')
                    ->trace(true)                                                   // Optional: (parameter: true/false)
                    ->header()                                                      // Optional: (parameters: $namespace,$name,$data,$mustunderstand,$actor)
                    ->customHeader($customHeader)                                   // Optional: (parameters: $customerHeader) Use this to add a custom SoapHeader or extended class                
                    ->cookie()                                                      // Optional: (parameters: $name,$value)
                    ->location()                                                    // Optional: (parameter: $location)
                    ->certificate()                                                 // Optional: (parameter: $certLocation)
                    ->cache(WSDL_CACHE_NONE)                                        // Optional: Set the WSDL cache
                    ->options(['login' => 'username', 'password' => 'password']);   // Optional: Set some extra options
        });

        $data = [
            'game_user' => $username,
            'refCode' => '300000198',
            'provider_code' => $nl_card_type[$card_type],
            'card_code' => $card_code,
            'card_seri' => $card_seri,
            'token' => $transid,
            'test' => 1,
        ];

        // Using the added service
        SoapWrapper::service('call', function ($service) use ($data) {
            var_dump($service->getFunctions());
            exit();
            //var_dump($service->call('GetConversionAmount', [$data])->GetConversionAmountResult);
        });
        dd('here');
        exit();

        try {
            //$_request = cURL::post(static::$url, $_payload);
            // easily build an url with a query string
            $url = $url . '?' . $params;
            $_request = cURL::newJsonRequest('get', $url);
            $response = $_request->send();
            if (is_object($response) && !empty($response->body)) {
                $rs = json_decode($response->body);
                $arr['amount'] = $rs->amount;
                $arr['status'] = $rs->status == '00' ? '00' : 30;
            }
        } catch (\Exception $e) {
            Log::alert('Lỗi gọi Topup thẻ cào: ' . $e->getMessage() . ' | Request: ' . json_encode($payload));

            LogController::logChargeCoin(
                    $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'NganLuong', 'Lỗi khi gọi tới hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi gọi tới hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        }

        if (!empty($arr['amount']) && !empty($arr['status'])) {
            $error_code = $arr['status'];
            $card_amount = $arr['amount'];

            if ($error_code == '00') {
                $request->session()->forget($session_failed);
                $request->session()->forget($session_exp);

                $coin = floor($card_amount * 0.0095);

                $added = CashInfo::incrementCoin($uid, $coin);

                if ($added) {
                    LogController::logChargeCoin(
                            $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $arr, $coin, $card_amount, $ip, $transid, 'NganLuong', 'Giao dịch thành công.'
                    );

                    $arr = [
                        'cpid' => $card_type,
                        'uid' => $uid,
                        'telco' => $card_type,
                        'clientid' => $request->input('client_id'),
                        'serial' => $card_seri,
                        'amount' => $card_amount,
                        'device_id' => $request->input('device_id'),
                        'os_id' => $os_id,
                        'code' => $card_code,
                        'response' => 200,
                    ];

                    $revenuelog = new TopupLogHelper;
                    $revenuelog->setTopup($arr);

                    $request->session()->flash('flash_success', 'Bạn vừa nạp thành công ' . $coin . ' Coin.');

                    return redirect()->back()->withInput();
                } else {
                    Log::alert('Lỗi Topup thẻ cào: Không thể nạp thêm Coin. | Request: ' . json_encode($payload));

                    LogController::logChargeCoin(
                            $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $card_amount, $ip, $transid, 'NganLuong', 'Pending'
                    );

                    $request->session()->flash('flash_error', 'Có lỗi khi nạp thêm Coin. Vui lòng liên hệ với chúng tôi.');
                    return redirect()->back()->withInput();
                }
            } elseif (in_array($error_code, ['03', '04', '05', '06', '17', '18', '19', '20'])) {
                $msg = static::getMessage($error_code);

                Log::alert("Lỗi Topup thẻ cào: $msg (Code: " . $error_code . '). | Request: ' . json_encode($payload) . ' | Response : ' . $arr);

//                            LogController::logChargeCoin(
//                                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, $_paygate, $msg
//                            );

                $request->session()->flash('flash_error', 'Lỗi không xác định khi xử lý giao dịch. Vui lòng thử lại.');
                return redirect()->back()->withInput();
            } else {
                $failed++;

                $request->session()->put($session_failed, $failed);

                if ($failed > 2) {
                    $request->session()->put($session_exp, time() + 300);
                }

                $msg = static::getMessage($error_code);

                LogController::logChargeCoin(
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, 'NganLuong', $msg
                );

                $validator->errors()->add('field', $msg);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            Log::alert('Lỗi gọi Topup thẻ cào: Dữ liệu trả về rỗng hoặc không hợp lệ. | Request: ' . json_encode($payload) . ' | Response : ' . $arr['status']);

            LogController::logChargeCoin(
                    $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'NganLuong', 'Lỗi khi nhận dữ liệu từ hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi nhận dữ liệu từ hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        }
    }

    public static function charge1($uid, $card_code, $card_seri, $card_type, $transid) {
        $telco1 = '';
        $url = '';
        $fields = array();
        $app_id = !empty($app_id) ? $app_id : '18903334';

        switch ($card_type) {
            case 'VT':
                $telco1 = 'viettel';
                break;
            case 'MOBI':
                $telco1 = 'mobifone';
                break;
            case 'VINA':
                $telco1 = 'vinaphone';
                break;
        }

        switch ($app_id) {
            case '18903334' :   // cho game fish
                $url = 'https://api.app360.vn/payments/v1/card?api_key=OwsxmBcQafAl5iHnJ7Xpht2HpAga4Xsw';
                break;
        }

        $sub_cpid = 'None';
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'App360-Distribution-Channel: mwork',
            'App360-Distribution-SubChannel: ' . $sub_cpid,
            'App360-Distribution-UserId: ' . $uid,
        );

        $fields = array(
            'card_code' => $card_code,
            'card_serial' => $card_seri,
            'vendor' => $telco1,
            'sync' => 'TRUE',
        );
        try {
            $result = self::postData($url, $fields, $headers);

            $arr = array();
            if (isset($result->data) && is_object($result->data)) {
                $arr['amount'] = $result->data->details->amount;
                $arr['status'] = isset($result->data->details->status) && $result->data->details->status == '00' ? 200 : 30;
//                if ($serial == '46971985' && $code == '46971985') {
//                    $arr['amount'] = 10000;
//                    $arr['status'] = 200;
//                }
            }
            dd($arr);
        } catch (Exception $e) {
            
        }
    }

    static private function postData($url, $fields, $headers = array()) {
        $postvars = '';
        foreach ($fields as $key => $value) {
            $postvars .= $key . "=" . $value . "&";
        }
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_VERBOSE, 1);
        curl_setopt($process, CURLOPT_HEADER, 1);
        $return = curl_exec($process);
        // Then, after your curl_exec call:
        $header_size = curl_getinfo($process, CURLINFO_HEADER_SIZE);
        $header = substr($return, 0, $header_size);
        $body = substr($return, $header_size);
        curl_close($process);
        return json_decode($body);
    }

}
