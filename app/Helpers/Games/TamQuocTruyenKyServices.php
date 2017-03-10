<?php

namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper,
    Exception;
use Illuminate\Support\Facades\Auth;

class TamQuocTruyenKyServices
{

    const SECRETKEY = 'GTHJ24F6S90ADFss2bAfAKDP'; // Secret key for FPAY Platform - FIX *  Key cho op.slg.vn
    const MTYPE = 1; // Money type - FIX For manga comic VNG
    const PAYTYPE = 1; // Pay type - FIX For manga comic VNG
    const API_LOGIN_URL = 'http://api.tamquoc.slg.vn/login/login';
    const API_PAYMENT_URL = 'http://api.tamquoc.slg.vn/recharge/pay';
    const API_ROLE_URL = 'http://api.tamquoc.slg.vn/server/checkUsers';
    const APP_ID_SLG = 18903335;
    const RATE = 0.01;
    const RATE_REV = 100;
    const PREFIX = 'slg';

    public function getLoginUrl($uid, $server_id = 's1', $from = 'slg')
    {
        if (Auth::check()) {
            $user = \Auth::user();
        } else {
            $user = \Auth::loginUsingId($uid);
        }

//        $username = strtolower($user->name);
        $userid = $user->id;
        $gameid = self::APP_ID_SLG;
        $time = time();
        $key = self::SECRETKEY;
        $al = 1;
        $from = '';
        $site_url = '';
        $sign = md5($userid . $gameid . $server_id . $key . $time . $al . $from . $site_url);
        $arr = array(
            //'Uname' => urlencode($username),
            'userid' => $userid,
            'GameId' => $gameid,
            'ServerId' => $server_id,
            'Sign' => $sign,
            //'Sign2' => $sign,
            'Time' => $time,
            'al' => $al,
            'from' => $from,
            'siteurl' => $site_url,
        );

        return (self::API_LOGIN_URL . '?' . http_build_query($arr));
    }

    private function generateToken(Array $arr)
    {
        $str = '';
        ksort($arr);    // sort array before hash

        foreach ($arr as $key => $value) {
            $str .= $key . '=' . $value;
        }

        return $token = md5($str . self::SECRETKEY);
    }

    public function transfer($uid, $server_id, $order_id, $coin)
    {
        if (Auth::check()) {
            $user = \Auth::user();
        } else {
            $user = \Auth::loginUsingId($uid);
        }
//        $_uid = $user->id;

        //http://api.tamquoc.slg.vn/recharge/pay?Depay=3&gDepay=0&addcoin=0&userid=999999&Money=10&GameId=18903335&ServiceId=s0&Transactionid=889257773066&paytype=topup&Sign=6bb482880f5d6686356064c02c7712f2
        $is_exist = $this->isExistingUser($uid, $server_id);
        $log = new LogCoinTransfer;

        $log->request_time = date('Y-m-d H:i:s');
        $log->user_id = $user->id;
        $log->server_id = $server_id;
        $log->app_id = self::APP_ID_SLG;
        $log->trans_info = 'Chuyển Coin vào Game.';
        $log->ip = CommonHelper::getClientIP();
        $log->trans_id = $order_id;
        $log->status = 0;
        $log->coin = $coin;

//        $vnd = $coin * self::RATE_REV;
        if (!$is_exist) {
            $log->response_time = date('Y-m-d H:i:s');
            $log->response = 'Người chơi không tồn tại.';
            if ($server_id > 0) {
                $log->save();
            }
            return false;
        }

        $userid = $user->id;
        $gameid = self::APP_ID_SLG;
//        $time = time();
        $key = self::SECRETKEY;
        $money = $coin;
        $depay = 1000;
        $gdepay = 0;
        $addcoin = 0;
        $paytype = '1';
        $transid = $order_id;
        $sign = md5($depay . $gdepay . $addcoin . $userid . $money . $gameid . $server_id . $transid . $paytype . $key);
        $arr = array(
            'Depay' => $depay,
            'gDepay' => $gdepay,
            'addcoin' => $addcoin,
            'userid' => $userid,
            'Money' => $money,
            'GameId' => $gameid,
            'ServiceId' => $server_id,
            'Transactionid' => $transid,
            'paytype' => $paytype,
            'Sign' => $sign
        );

        try {
//            $url = self::API_PAYMENT_URL . '?' . http_build_query($arr);
            $_response = $this->request(self::API_PAYMENT_URL . '?' . http_build_query($arr));
            $payment_response = str_replace(array('\ufeff', 'ï»¿'), '', utf8_encode($_response));
        } catch (\Exception $e) {
            $payment_response = 'cURL Error: ' . $e->getMessage();
        }

        $response = trim($payment_response);

        $log->response_time = date('Y-m-d H:i:s');

        if ($response == 1) {
            $log->status = 1;
            $response = 'success';
        }

        $log->response = $response;

        if ($server_id > 0) {
            $log->save();
        }

        return $response;
    }

    public function isExistingUser($uid, $server_id)
    {
        //http://api.tamquoc.slg.vn/server/checkUsers/?userid=25&type=1&sid=S1
        $arr = array(
            'userid' => $uid,
            'type' => 1,
            'sid' => $server_id,
        );
        try {
            $response = $this->request(self::API_ROLE_URL . '?' . http_build_query($arr));
        } catch (Exception $e) {
            echo 'check user error: ', $e->getMessage(), "\n";
        }
        return $response;
    }

    private function request($url, $data = false)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        if ($data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

}
