<?php

namespace App\Helpers\Payments;

use cURL, App\Http\Controllers\Util\LogController, App\Models\CashInfo;
use Log, App\Helpers\Logs\TopupLogHelper;

class CyberPay
{
    public static $vendor_id = 6;//10007 test
    public static $secret_key = 'e7feea46776fc4b04ffb1b7d04fdd5ba861ac48d';
    public static $url = 'https://vendorpin.cyberpay.vn/api/topup/';
    //    public static $secret_key = 'd99213d2783606d5bca67e8155ffc5ebd3fc3dc6'; test
    //    public static $url = 'https://testpin.vendor.cyberpay.vn/api/topup/'; test

    public static function getMessage($code)
    {
        switch ($code) {
            default:
                return '';
            case '0':
                return 'Giao dịch thành công.';
            case '1':
                return 'Sai địa chỉ IP.';
            case '2':
                return 'Phương thức gọi không hợp lệ.';
            case '3':
                return 'Thiếu một số trường bắt buộc.';
            case '4':
                return 'Một số trường không hợp lệ.';
            case '5':
                return 'Lỗi hệ thống.';
            case '6':
                return 'Loại thẻ không tồn tại.';
            case '7':
                return 'Vendor không tồn tại.';
            case '8':
                return 'Thông tin Topup không khớp.';
            case '9':
                return 'Topup không tồn tại.';
            case '10':
                return 'Topup đã tồn tại.';
            case '11':
                return 'Thẻ không tồn tại.';
            case '12':
                return 'Thẻ đã được sử dụng.';
            case '13':
                return 'Thẻ chưa được sử dụng.';
            case '14':
                return 'Trạng thái thẻ không hợp lệ.';
            case '15':
                return 'Mã PIN thẻ không hợp lệ.';
            case '16':
                return 'Lỗi khi gạch hai thẻ cùng số Serial cùng một thời điểm.';
            case '17':
                return 'Mã tham chiếu đã thuộc về thẻ khác.';
            case '20':
                return 'Thẻ đã hết hạn.';
        }
    }

    public static function charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed, $os_id = '')
    {
        $payload = [
            'pin' => $card_code,
            'reference' => $transid,
            'serial' => $card_seri,
            'vendor_id' => static::$vendor_id,
            'secret_key' => static::$secret_key,
        ];

        $payload['signature'] = sha1(urldecode(http_build_query($payload)));

        unset($payload['secret_key']);

        try {
            $_request = cURL::post(static::$url, $payload);
//                    khi test api, tat SSL verify
//                    $_request_ = cURL::newRequest('post', static::$url, $payload)
//                        ->setOption(CURLOPT_SSL_VERIFYPEER, false)
//                        ->setOption(CURLOPT_SSL_VERIFYHOST, false);
//
//                    $_request = $_request_->send();
        } catch (\Exception $e) {
            Log::alert("Lỗi gọi Topup thẻ CyberPay: " . $e->getMessage() . ' | Request: ' . json_encode($payload));

            LogController::logChargeCoin(
                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'CyberPay', 'Lỗi khi gọi tới hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi gọi tới hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        }


        $response = json_decode($_request->body, true);

        if (empty($response) || !is_array($response)) {
            $_response = (string)$response;

            Log::alert("Lỗi gọi Topup thẻ CyberPay: Dữ liệu trả về rỗng hoặc không hợp lệ. | Request: " . json_encode($payload) . ' | Response : ' . $_response);

            LogController::logChargeCoin(
                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'CyberPay', 'Lỗi khi nhận dữ liệu từ hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi nhận dữ liệu từ hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        } else {
            if ($response['code'] == '0') {
                $request->session()->forget($session_failed);
                $request->session()->forget($session_exp);

                $coin = floor($response['topup']['denomination'] * 0.0095);

                $added = CashInfo::incrementCoin($uid, $coin);

                if ($added) {
                    LogController::logChargeCoin(
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $response['topup']['denomination'], $ip, $transid, 'CyberPay', 'Giao dịch thành công.'
                    );

                    $arr = [
                        'cpid' => $card_type,
                        'uid' => $uid,
                        'telco' => $card_type,
                        'clientid' => $request->input('client_id'),
                        'serial' => $card_seri,
                        'amount' => $response['topup']['denomination'],
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
                    Log::alert("Lỗi Topup thẻ cào CyberPay: Không thể nạp thêm Coin. | Request: " . json_encode($payload));

                    LogController::logChargeCoin(
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $response['topup']['denomination'], $ip, $transid, 'CyberPay', 'Pending'
                    );

                    $request->session()->flash('flash_error', 'Có lỗi khi nạp thêm Coin. Vui lòng liên hệ với chúng tôi.');
                    return redirect()->back()->withInput();
                }
            } elseif (in_array($response['code'], ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '16', '17'])) {
                $msg = static::getMessage($response['code']);

                Log::alert("Lỗi Topup thẻ cào CyberPay: $msg (Code: " . $response['code'] . '). | Request: ' . json_encode($payload) . ' | Response : ' . $_request->body);

//                        LogController::logChargeCoin(
//                            $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, 'CyberPay', $msg
//                        );

                $request->session()->flash('flash_error', 'Lỗi không xác định khi xử lý giao dịch. Vui lòng thử lại.');
                return redirect()->back()->withInput();
            } else {
                $failed++;

                $request->session()->put($session_failed, $failed);

                if ($failed > 2) {
                    $request->session()->put($session_exp, time() + 300);
                }

                $msg = static::getMessage($response['code']);

                if ($msg === '') {
                    $msg = 'Lỗi không xác định khi xử lý giao dịch. Vui lòng liên hệ với chúng tôi.';

                    LogController::logChargeCoin(
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, 'CyberPay', $msg
                    );
                }

                LogController::logChargeCoin(
                    $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, 'CyberPay', $msg
                );

                $validator->errors()->add('field', $msg);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    }
}