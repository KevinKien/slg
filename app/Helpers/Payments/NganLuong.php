<?php

namespace App\Helpers\Payments;

use cURL, App\Http\Controllers\Util\LogController, App\Models\CashInfo;
use Log, App\Helpers\Logs\TopupLogHelper, App\Models\UserGame;

class NganLuong
{
//    public static $merchant_id = '47138';
//    public static $merchant_account = 'huett@vinhxuan.com.vn';
//    public static $password = 'd1edb687fc429eef4c78162fc3a010a0';

//    public static $merchant_id = '40643';
//    public static $merchant_account = 'phuongnb@vinhxuan.com.vn';
//    public static $password = '1forever';

    private $merchant_id;
    private $merchant_account;
    private $password;
    private $url;

    public static $game_id = 18903335; //TQTK
    public static $version = '2.0';

    public function __construct($uid = false)
    {
//        $date = date('d-m');
//
//        if (in_array($date, ['26-01', '27-01', '28-01', '29-01', '30-01', '31-01', '01-02', '02-02', '03-02', '04-02', '05-02', '06-02'])) {
//            $this->setVATConfig();
//        } else {
            $this->setDefaultConfig();

            if ($uid) {
                $is_tqtk = UserGame::where('uid', $uid)->where('app_id', static::$game_id)->first();
                if ($is_tqtk) {
                    $this->setVATConfig();
                }
            }
//        }
    }

    public function setDefaultConfig()
    {
        $this->url = 'https://www.nganluong.vn/mobile_card.api.post.v2.php';

        $this->merchant_id = '48201';
        $this->merchant_account = 'huenhi@vinhxuan.com.vn';
        $this->password = '7235e76be7d0d8e9182114db4123e729';
    }

    public function setVATConfig()
    {
        $this->url = 'http://exu.vn/mobile_card.api.post.v2.php';

        $this->merchant_id = '48200';
        $this->merchant_account = '0977998781@vinhxuan.com.vn';
        $this->password = '0abe5c505a081397d8faa896eb6a7155';
    }

    public static function getMessage($code)
    {
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

    public function charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed, $os_id = '')
    {
        $nl_card_type = [
            'MOBI' => 'VMS',
            'VINA' => 'VNP',
            'VT' => 'VIETTEL',
            'GATE' => 'GATE',
        ];

        if ($card_type == 'GATE') {
            $this->setDefaultConfig();
        }

        $payload = $_payload = [
            'func' => 'CardCharge',
            'version' => static::$version,
            'merchant_id' => $this->merchant_id,
            'merchant_account' => $this->merchant_account,
            'merchant_password' => md5($this->merchant_id . '|' . $this->password),
            'pin_card' => $card_code,
            'card_serial' => $card_seri,
            'type_card' => $nl_card_type[$card_type],
            'ref_code' => $transid,
        ];

        $payload['merchant_id'] = null;
        $payload['merchant_account'] = null;

        try {
            $_request = cURL::post($this->url, $_payload);
        } catch (\Exception $e) {
            Log::alert('Lỗi gọi Topup thẻ cào: ' . $e->getMessage() . ' | Request: ' . json_encode($payload));

            LogController::logChargeCoin(
                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'NganLuong', 'Lỗi khi gọi tới hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi gọi tới hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        }

        $response = explode('|', $_request->body);

        if (count($response) == 13) {
            $error_code = $response[0];
            $card_amount = $response[10];

            if ($error_code == '00') {
                $request->session()->forget($session_failed);
                $request->session()->forget($session_exp);

                $coin = floor($card_amount * 0.0095);

                $added = CashInfo::incrementCoin($uid, $coin);

                if ($added) {
                    LogController::logChargeCoin(
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $card_amount, $ip, $transid, 'NganLuong', 'Giao dịch thành công.'
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

                Log::alert("Lỗi Topup thẻ cào: $msg (Code: " . $error_code . '). | Request: ' . json_encode($payload) . ' | Response : ' . $_request->body);

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
            Log::alert('Lỗi gọi Topup thẻ cào: Dữ liệu trả về rỗng hoặc không hợp lệ. | Request: ' . json_encode($payload) . ' | Response : ' . $_request->body);

            LogController::logChargeCoin(
                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'NganLuong', 'Lỗi khi nhận dữ liệu từ hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi nhận dữ liệu từ hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        }
    }
}