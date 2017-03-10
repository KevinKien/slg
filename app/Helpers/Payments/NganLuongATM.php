<?php
/**
 * Created by PhpStorm.
 * User: vuong
 * Date: 12/1/2016
 * Time: 2:07 PM
 */

namespace App\Helpers\Payments;


class NganLuongATM
{
    public static $bank_url = 'https://www.nganluong.vn/checkout.php';
    public static $merchant_id = '48200';
    public static $merchant_account = '0977998781@vinhxuan.com.vn';
    public static $password = '0abe5c505a081397d8faa896eb6a7155';

    //Hàm xây dựng url, trong đó có tham số mã hóa (còn gọi là public key)
    public static function buildCheckoutUrl($order_code, $price, $transaction_info)
    {
        // Mảng các tham số chuyển tới nganluong.vn
        $arr_param = [
            'merchant_site_code' => strval(static::$merchant_id),
            'return_url' => strtolower(urlencode(route('topupcash.get.nl.callback'))),
            'receiver' => strval(static::$merchant_account),
            'transaction_info' => strval($transaction_info),
            'order_code' => strval($order_code),
            'price' => strval($price),
        ];

        $secure_code = implode(' ', $arr_param) . ' ' . static::$password;
        $arr_param['secure_code'] = md5($secure_code);

        /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
        $redirect_url = static::$bank_url;
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?';
        } else if (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false) {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }

        /* Bước 3. tạo url*/
        $url = '';
        foreach ($arr_param as $key => $value) {
            if ($key != 'return_url') $value = urlencode($value);

            if ($url == '')
                $url .= $key . '=' . $value;
            else
                $url .= '&' . $key . '=' . $value;
        }

        return $redirect_url . $url;
    }

    /*Hàm thực hiện xác minh tính đúng đắn của các tham số trả về từ nganluong.vn*/

    public static function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
    {
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
        if ($verify_secure_code === $secure_code) return true;

        return false;
    }

}