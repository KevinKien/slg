<?php

namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper,
    Exception;
use Auth;

class LinhVuongServices {

    const TOKEN = '0BC2F146-4EF3-9E0A-7CCC-58ACB84DD303';
    const API_PAYMENT_URL = 'http://s01.linhvuong.slg.vn:8011/AddToken.aspx';
    const LINHVUONG_PAYMENT_URL = 'http://linhvuong.slg.vn:8888/recharge_fpay.php';
    const P_TYPE = 1;
    const APP_ID = 1001;
    const RATE = 0.01;
    const RATE_REV = 100;
    const PREFIX = 'slg';

    private function getCorrectUID($uid) {
        return (strpos($uid, self::PREFIX) === false) ? $uid : str_replace('slg', '', $uid);
    }

    public function transfer($id, $server, $order_id, $coin, $log_to_db = 1) {
        if (Auth::check()) {
            $user = \Auth::user();
        } else {
            $user = \Auth::loginUsingId($id);
        }
        $username = urlencode($user->name);

        $id = str_replace('slg', '', $id);
        $request_id = $this->genarate_requestid($id);
        $telco = 'FPAY';
//        $request_id = genarate_requestid($uid);// Ma don hang (ko can lam gi)
        $s_key = "dgspjfdFfdsR234F433sdr3rd2d3f5sa43"; // Key (ko can lam gi)
        $pid = "113"; //(ko can lam gi)
        $ptime = date('YmdHisu', strtotime('+8 hours')); // Thoi gian (ko can lam gi)
        $monney = $coin * self::RATE_REV;
        $data_sign = self::APP_ID . '|' . $id . '|' . $username . '|' . $telco . '|' . $monney . '|' . $request_id . '|' . $server . '|' . self::TOKEN;
        $sign_game = md5($data_sign);
//        $check_discount = $this->checkDiscounts($telco, $amount_fpay);
        $amount = $coin * self::RATE;
        $check_sign = md5($id . $username . $request_id . $server . $coin . $amount . $pid . $s_key);

        $transfer_option = 1;
        /* =======Chuyen coin vao game======== */
        if ($transfer_option == 1) {

            $arr = array(
                'uid' => $id,
                'uname' => $username,
                'orderid' => $request_id,
                'serverid' => $server,
                'points' => $coin,
                'amount' => $coin * self::RATE,
                'pid' => $pid,
                'ptime' => $ptime,
                'ptype' => self::P_TYPE,
                'sign' => $check_sign
            );
            $payment_url = self::API_PAYMENT_URL . '?' . http_build_query($arr);
            if ($user->id == '5352711') {
                //dd($payment_url);
            }
            try {
                $_response = $this->post($payment_url);
                $payment_response = str_replace(array('\ufeff', 'ï»¿'), '', utf8_encode($_response));
            } catch (\Exception $e) {
                $payment_response = 'cURL Error: ' . $e->getMessage();
            }
            $response = trim($payment_response);
        } else {
            $arr = array(
                'app_id' => self::APP_ID,
                'uid' => $id,
                'username' => urlencode($username),
                'amount' => $coin * self::RATE_REV,
                'server' => $server,
                'request_id' => $request_id,
                'sign' => $sign_game,
                'telco' => $telco
            );
            $payment_url = self::LINHVUONG_PAYMENT_URL . '?' . http_build_query($arr);
            $_response = $this->post($payment_url);
            if ($_response == 1) {
                $response = 'success';
            } else {
                $response = 'fail';
            }
        }
        /* =======Chuyen coin vao game======== */

        /* ==========Log chuyen coin========== */
        if ($log_to_db) {
            // only log for linhvuong - slg
            $log = new LogCoinTransfer;
            $log->request_time = date('Y-m-d H:i:s');
            $log->user_id = $user->id;
            $log->server_id = $server;
            $log->app_id = self::APP_ID;
            $log->trans_info = 'Chuyển Coin vào Game.';
            $log->ip = CommonHelper::getClientIP();
            $log->trans_id = $request_id;
            $log->status = 0;
            $log->coin = $coin;
            /* ==========Log chuyen coin========== */
            $log->response_time = date('Y-m-d H:i:s');

            if ($response == 'success') {
                $log->status = 1;
            }

            $log->response = $response;

            $log->save();
        }

        return $response;
    }

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

    private function genarate_requestid($uid) {
        $m_PartnerCode = '00711';
        if ($uid != 0) {
            $guid = $m_PartnerCode . '_' . $uid . '_' . uniqid();
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $guid = substr($charid, 0, 8) . '-' .
                    substr($charid, 8, 4) . '-' .
                    substr($charid, 12, 4) . '-' .
                    substr($charid, 16, 4) . '-' .
                    substr($charid, 20, 4);
        }

        return $guid;
    }

    private function post($url, $data = false) {
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

    public function getLoginUrl($user, $server_id, $pid = 113) {
        $url = 'http://' . $server_id . '.linhvuong.slg.vn/';
        $a = date('YmdHis', strtotime('+5 min'));
        $loginkey = "dgspjfdFfdsR234F433sdr3rd2d3f5sa43";
        $sign = md5($user->id . $user->name . $a . $pid . $loginkey);
        //$http = $login_ip;
        $url = $url . 'Interfaces/login_partner.aspx?uid=' . $user->id . '&uname=' . $user->name . '&ulgtime=' . $a . '&pid=' . $pid . '&sign=' . $sign;
        return $url;
    }

}
