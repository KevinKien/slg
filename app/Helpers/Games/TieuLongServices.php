<?php

namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper;

class TieuLongServices {

    const TYPE = 12;   //num of the platform - FIX
    const PAYMENT_URL = 'http://gold-vi.seastar-games.net/api/pay/sologame/callback.php';
    const PAYMENT_URL_DEV = 'http://gold-vi.seastar-games.net/api/pay/sologame/callback.php';
    const CLIENT_ID = '8633283045';
    const CLIENT_SECRET = 'qQTPEmOXhKSi2jkVHRMH';
    const APP_ID_SLG = 18903334;

    private function getCorrectUID($uid) {
        return 'getCorrectUID';
    }

    public function getLoginUrl($uid, $server_id) {
        return 'getLoginUrl';
    }

    private function generateToken(Array $arr) {
        return 'generateToken';
    }

    public function transfer($uid, $server_id, $order_id, $coin, $ip = 0, $is_dev = false) {
        // create payment info
        $data = array();
        $time = time();

        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

        $data['uid'] = $uid;
        $data['server_id'] = $server_id;
        $data['client_id'] = self::CLIENT_ID;
        $data['client_secret'] = self::CLIENT_SECRET;
        $data['trans_id'] = $order_id;
        $data['amount'] = $coin;
        switch ($coin){
            case 95:
                $data['amount'] = 100;
                break;
            case 190:
                $data['amount'] = 200;
                break;
            case 475:
                $data['amount'] = 500;
                break;
            case 950:
                $data['amount'] = 1000;
                break;
            case 1900:
                $data['amount'] = 2000;
                break;
            case 4750:
                $data['amount'] = 5000;
                break;
            case 9500:
                $data['amount'] = 10000;
                break;
        }
        $data['time'] = $time;
        $sign = md5(
            $uid .
            $server_id .
            self::CLIENT_ID .
            self::CLIENT_SECRET .
            $order_id .
            $data['amount'] .
            $time
        );
        $data['sign'] = $sign;

        $url = $is_dev ? self::PAYMENT_URL_DEV . '?' : self::PAYMENT_URL . '?';

        $url .= http_build_query($data);

        $response = $this->getCurl($url);
        $response = json_decode($response);

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

        if (is_object($response) && isset($response->error_code) && $response->error_code == 200) {
            $log->response = $response->error_message;
            $log->status = 1;

            $result = true;
        } else {
            $result = false;
        }

        if (!$is_dev)
        {
            $log->save();
        }

        return $result;
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
