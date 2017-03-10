<?php

namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper;

class DaiThanhG3Services {

    const TYPE = 12;   //num of the platform - FIX
    const PAYMENT_URL = 'http://soul.slg.vn:62400/chid_yuenan/api/payment';
    const PAYMENT_URL_DEV = 'http://123.30.145.151:62400/chid_yuenan/api/payment';
    const CLIENT_ID = '9194439505';
    const CLIENT_SECRET = 'P634FgjZuSgMAkKgC2yj';
    const APP_ID_SLG = 18903329;

    private function getCorrectUID($uid) {
        return 'getCorrectUID';
    }

    public function getLoginUrl($uid, $server_id) {
        return 'getLoginUrl';
    }

    private function generateToken(Array $arr) {
        return 'generateToken';
    }

    public function transfer($uid, $server_id, $order_id, $coin, $ip = 0, $is_dev = FALSE) {
        // create payment info
        $data = array();
        $time = time();

        $data['uid'] = $uid;
        $data['server_id'] = $server_id;
        $data['client_id'] = self::CLIENT_ID;
        $data['client_secret'] = self::CLIENT_SECRET;
        $data['trans_id'] = $order_id;
        $data['amount'] = $coin;
        $data['time'] = $time;
        $sign = md5(
                $uid .
                $server_id .
                self::CLIENT_ID .
                self::CLIENT_SECRET .
                $order_id .
                $coin .
                $time
        );
        $data['sign'] = $sign;


        $str = (json_encode($data));
        $data_post = array('data' => $str);

        $url = $is_dev ? self::PAYMENT_URL_DEV . '?' : self::PAYMENT_URL . '?';
        
        $url .= http_build_query($data_post);

        $reponse = $this->getCurl($url);

        $log = new LogCoinTransfer;

        $log->request_time = date('Y-m-d H:i:s');
        $log->user_id = $uid;
        $log->server_id = $server_id;
        $log->app_id = self::APP_ID_SLG;
        $log->trans_info = 'Chuyển Coin vào Game.';
        $log->ip = CommonHelper::getClientIP();
        $log->trans_id = $order_id;
        $log->status = 0;
        $log->coin = $coin;
        $log->response_time = date('Y-m-d H:i:s');
        $log->response = 'Lỗi không xác định.';

        if ($reponse)
        {
            $log->response = 'Thành Công';
            $log->status = 1;
        }

        if (!$is_dev)
        {
            $log->save();
        }

        return $reponse;
    }

    public function isExistingUser($uid, $server_id) {
        return 'isExistingUser';
    }

    public function getRole($uid, $server_id) {
        return 'getRole';
    }

    private function post($url, $data) {
        return 'post';
    }

    private function getCurl($url) {
        // Get cURL resource
        $curl = curl_init();

        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'SLG call payment'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }

    private function postCurl($url, $param) {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'SLG call payment',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $param
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }

}
