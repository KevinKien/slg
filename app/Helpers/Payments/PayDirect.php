<?php

namespace App\Helpers\Payments;

use App\Helpers\ThirdParty\MWork;
use CommonHelper;
use cURL, App\Http\Controllers\Util\LogController, App\Models\CashInfo;
use Log, App\Helpers\Logs\TopupLogHelper;


class PayDirect
{
    public static $provider = 'PAYDIRECT';//@todo: bỏ
    public static $partnerCode = 'VINHXUAN';
//    public static $partnerCode = 'FPAY';
    public static $password = 'vx@1234';
//    public static $password = 'fpay@1234';
    public static $secretKey = 'vx@sk1234';
//    public static $secretKey = 'fpay@sk1234';
    public static $url = 'http://125.212.219.11/voucher/rest/useCard';
//    public static $url = 'http://202.160.125.55/voucher/rest/useCard';
    public static $transaction_url = 'http://125.212.219.11/voucher/rest/getTransactionDetail';
//    public static $transaction_url = 'http://202.160.125.55/voucher/rest/getTransactionDetail';

    public static function getMessage($code)
    {
        switch ($code) {
            default:
                return '';
            case '00':
                return 'Mã số nạp tiền không tồn tại hoặc đã được sử dụng.';
            case '01':
                return 'Giao dịch thành công.';
            case '03':
                return 'Thẻ đã được sử dụng.';
            case '04':
                return 'Thẻ đã bị khóa.';
            case '05':
                return 'Thẻ đã hết hạn sử dụng.';
            case '06':
                return 'Thẻ chưa được kích hoạt.';
            case '07':
                return 'Thực hiện sai quá số lần cho phép.';
            case '08':
                return 'Giao dịch nghi vấn (Timeout từ Đơn vị phát hành thẻ, chưa xử lý xong).';
            case '09':
                return 'Sai định dạng thông tin truyền vào.';
            case '10':
                return 'Partner không tồn tại.';
            case '11':
                return 'Partner bị khóa.';
            case '13':
                return 'Hệ thống của Đơn vị phát hành thẻ đang bận.';
            case '14':
                return 'Sai password.';
            case '15':
                return 'Sai địa chỉ IP.';
            case '16':
                return 'Sai chữ ký.';
            case '20':
                return 'Sai độ dài mã số nạp tiền.';
            case '21':
                return 'Mã giao dịch không hợp lệ (> 0 và < 30 ký tự).';
            case '23':
                return 'Serial thẻ không hợp lệ.';
            case '24':
                return 'Mã số nạp tiền và serial không khớp.';
            case '25':
                return 'Trùng mã giao dịch (transRef).';
            case '26':
                return 'Mã giao dịch không tồn tại.';
            case '28':
                return 'Mã số nạp tiền không đúng định dạng (chỉ bao gồm ký tự số).';
            case '40':
                return 'Lỗi kết nối tới Đơn vị phát hành.';
            case '41':
                return 'Lỗi khi Đơn vị phát hành thẻ xử lý giao dịch (Lỗi phát sinh khi đơn vị phát hành thẻ đang xử lý giao dịch).';
            case '51':
                return 'Đơn vị phát hành thẻ không tồn tại.';
            case '52':
                return 'Đơn vị phát hành thẻ không hỗ trợ nghiệp vụ này.';
            case '99':
                return 'Lỗi không xác định khi xử lý giao dịch .';
        }
    }

    public static function getTransaction($card_code = '', $card_seri = '')
    {
        $cookie = tempnam(sys_get_temp_dir(), 'cookie');

        CommonHelper::cURL('http://202.160.125.63:8180/web/guest/tai-khoan', $cookie);

        $payload = 'save_last_path=0&_ext_login_rememberMe=true&_ext_login_p_p_assetType=&_ext_login_redirect=&_ext_login_p_p_uid=&_ext_login_loginAttempt=0&_ext_login_redirectForgetPassword=http%3A%2F%2Fpaydirect.vn%2Fweb%2Fguest%2Ftai-khoan%3Fp_p_id%3Dext_login%26p_p_lifecycle%3D0%26p_p_state%3Dnormal%26p_p_mode%3Dview%26p_p_col_id%3Dcolumn-1%26p_p_col_count%3D1%26_ext_login_struts_action%3D%252Fext%252Fmy_street%252Fforget_password&_ext_login_redirectNewAccount=http%3A%2F%2Fpaydirect.vn%2Fweb%2Fguest%2Ftai-khoan%3Fp_p_id%3Dext_login%26p_p_lifecycle%3D0%26p_p_state%3Dnormal%26p_p_mode%3Dview%26p_p_col_id%3Dcolumn-1%26p_p_col_count%3D1%26_ext_login_struts_action%3D%252Fext%252Fmy_street%252Fcreate_new_account&_ext_login_redirectView=http%3A%2F%2Fpaydirect.vn%2Fweb%2Fguest%2Ftai-khoan%3Fp_p_id%3Dext_login%26p_p_lifecycle%3D0%26p_p_state%3Dnormal%26p_p_mode%3Dview%26p_p_col_id%3Dcolumn-1%26p_p_col_count%3D1%26_ext_login_struts_action%3D%252Fext%252Fmy_street%252Fview&_ext_login_login=' . urlencode(static::$partnerCode) . '&_ext_login_password=' . urlencode(static::$password);

        $login = CommonHelper::cURL('http://202.160.125.63:8180/web/guest/tai-khoan?p_p_id=ext_login&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_ext_login_struts_action=%2Fext%2Fext_login%2Fview&_ext_login_cmd=update', $cookie, $payload);

        $result = [];

        if (str_contains($login, 'Thoát')) {
//            $search = CommonHelper::cURL("http://202.160.125.63:8180/web/backend/khach-hang/tra-cuu-gd?p_p_id=vcard_searchCard_INSTANCE_66Fv&p_p_lifecycle=0&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_vcard_searchCard_INSTANCE_66Fv_struts_action=%2Fvcard_searchCard%2FsearchCardBySerial&_vcard_searchCard_INSTANCE_66Fv_strfromdate=&_vcard_searchCard_INSTANCE_66Fv_strtodate=&_vcard_searchCard_INSTANCE_66Fv_strSerial=$card_seri&_vcard_searchCard_INSTANCE_66Fv_strTranRefId=&_vcard_searchCard_INSTANCE_66Fv_strProvider=0&_vcard_searchCard_INSTANCE_66Fv_strCardCode=$card_code", $cookie);
            $search = CommonHelper::cURL("http://202.160.125.63:8180/web/backend/khach-hang/tra-cuu-gd?p_p_id=vcard_searchCard_INSTANCE_Nl1w&p_p_lifecycle=0&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_vcard_searchCard_INSTANCE_Nl1w_struts_action=%2Fvcard_searchCard%2FsearchCardBySerial&_vcard_searchCard_INSTANCE_Nl1w_strfromdate=&_vcard_searchCard_INSTANCE_Nl1w_strtodate=&_vcard_searchCard_INSTANCE_Nl1w_strSerial=$card_seri&_vcard_searchCard_INSTANCE_Nl1w_strTranRefId=&_vcard_searchCard_INSTANCE_Nl1w_strProvider=0&_vcard_searchCard_INSTANCE_Nl1w_strCardCode=$card_code", $cookie);

            if (str_contains($search, 'id="content_tr"')) {
                preg_match_all('#<tr id="content_tr"[^>]*>(.*?)</tr>#is', $search, $lines);

                foreach ($lines[1] as $k => $line) {
                    preg_match_all('#<td[^>]*>(.*?)</td>#is', $line, $cells);

                    foreach ($cells[1] as $cell) {
                        $result[] = $cell;
                    }
                }
            }
        }

        return $result;
    }

    public static function charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator, $session_failed, $session_exp, $failed, $os_id = '')
    {
        $payload = $_payload = [
            'issuer' => $card_type,
            'cardSerial' => $card_seri,
            'cardCode' => $card_code,
            'amount' => '0',
            'serviceCode' => '0',
            'transRef' => $transid,
            'partnerCode' => static::$partnerCode,
            'password' => static::$password,
            'accountId' => (string)$uid,
            'signature' => md5($card_type . $card_code . $transid . static::$partnerCode . static::$password . static::$secretKey),
        ];

        $payload['partnerCode'] = null;
        $payload['password'] = null;

        try {
            $_request = cURL::jsonPost(static::$url, $_payload);
        } catch (\Exception $e) {
            Log::alert('Lỗi gọi Topup thẻ cào: ' . $e->getMessage() . ' | Request: ' . json_encode($payload));

            LogController::logChargeCoin(
                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'PayDirect', 'Lỗi khi gọi tới hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi gọi tới hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        }

        $response = json_decode($_request->body, true);

        if (empty($response) || !is_array($response)) {
            $_response = (string)$response;

            Log::alert('Lỗi gọi Topup thẻ cào: Dữ liệu trả về rỗng hoặc không hợp lệ. | Request: ' . json_encode($payload) . ' | Response : ' . $_response);

            LogController::logChargeCoin(
                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, 0, 0, $ip, $transid, 'PayDirect', 'Lỗi khi nhận dữ liệu từ hệ thống thanh toán.'
            );

            $request->session()->flash('flash_error', 'Có lỗi khi nhận dữ liệu từ hệ thống thanh toán, vui lòng thử lại.');
            return redirect()->back()->withInput();
        } else {
            if ($response['status'] == '01') {
                $request->session()->forget($session_failed);
                $request->session()->forget($session_exp);

                $coin = floor($response['amount'] * 0.0095);

                $added = CashInfo::incrementCoin($uid, $coin);

                if ($added) {
                    LogController::logChargeCoin(
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $response['amount'], $ip, $transid, 'PayDirect', 'Giao dịch thành công.'
                    );

                    $arr = [
                        'cpid' => $card_type,
                        'uid' => $uid,
                        'telco' => $card_type,
                        'clientid' => $request->input('client_id'),
                        'serial' => $card_seri,
                        'amount' => $response['amount'],
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
                        $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $response['amount'], $ip, $transid, 'PayDirect', 'Pending'
                    );

                    $request->session()->flash('flash_error', 'Có lỗi khi nạp thêm Coin. Vui lòng liên hệ với chúng tôi.');
                    return redirect()->back()->withInput();
                }
            } elseif (in_array($response['status'], ['08', '13', '41', '99'])) {
                $msg = static::getMessage($response['status']);

                Log::alert("Lỗi Topup thẻ cào: $msg (Code: " . $response['status'] . '). | Request: ' . json_encode($payload) . ' | Response : ' . $_request->body);

//                            LogController::logChargeCoin(
//                                $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, $_paygate, $msg
//                            );

                $request->session()->flash('flash_error', 'Lỗi không xác định khi xử lý giao dịch. Vui lòng thử lại.');
                return redirect()->back()->withInput();
            } else {
                if ($response['status'] == '11') {
                    Log::alert('Lỗi Topup thẻ cào: Partner bị khóa. | Request: ' . json_encode($payload));
                }

                $failed++;

                $request->session()->put($session_failed, $failed);

                if ($failed > 2) {
                    $request->session()->put($session_exp, time() + 300);
                }

                $msg = static::getMessage($response['status']);

                LogController::logChargeCoin(
                    $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, 0, 0, $ip, $transid, 'PayDirect', $msg
                );

                $validator->errors()->add('field', $msg);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    }
}