<?php

namespace App\Helpers\Games;

use CommonHelper;

class ThienLongServices {

    const TYPE = 12;   //num of the platform - FIX
    const PAYMENT_URL = 'http://123.30.145.253:5000/daxia/http_iface:recharge_yuenan_new';
    const PAYMENT_URL_DEV = 'http://123.30.145.253:5000/daxia/http_iface:recharge_yuenan_new';
    const CLIENT_ID = '2044387389';
    const CLIENT_SECRET = 'mqn5EndQAnoZEYfop5CN';

    private function checkDiscounts($telco, $amount_fpay) {
        if (($telco == 'FPAY' || $telco == 'VND') && $amount_fpay >= 100000) {
            $time_start = strtotime('2014-07-30 00:00:00');
            $time_end = strtotime('2015-08-30 23:00:00');
            $time_current = time();
            if ($time_current > $time_start && $time_current < $time_end) {
                $check = 1;
            } else {
                $check = 0;
            }
        } else {
            $check = 0;
        }
        return $check;
    }

    private function format_price($price) {
        return $price / 100;
    }

    private function format_amount($price) {
        return $price / 10000;
    }

    //Tinh toán Vcoin với khuyến mại
    private function format_price_discounts($price) {
        return $price / 100 * 1.1;
    }

    private function format_amount_discounts($price) {
        return $price / 10000 * 1.1;
    }

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

        // validate request
        //$uid = '456583';
        $data['uid'] = $uid;
        $data['server_id'] = $server_id;
        $data['client_id'] = self::CLIENT_ID;
        $data['client_secret'] = self::CLIENT_SECRET;
        $data['trans_id'] = $order_id;
        $data['amount'] = intval($coin);
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
        //dd($data_post);
        $url = $is_dev ? self::PAYMENT_URL_DEV . '?' : self::PAYMENT_URL . '?';

        $url .= http_build_query($data_post);
        // cache log 
        //Redis::set('log_step3', $url);
        //$url = 'http://api.slg.vn/apiv1/me?access_token=GqyUmSANdgf7xiauErn4bkDAA8Zj0YAW5jmCe0AW';
        //dd($url);
        $reponse = $this->getCurl($url);
        $arr = json_decode($reponse);

        if (is_object($arr) && isset($arr->error_code) && $arr->error_code == 200) {
            return TRUE;
        }
        //dd($arr['error_code']);
        //$reponse = $this->postCurl(self::PAYMENT_URL, $data_post);
        //dd($url);
        return FALSE;
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
