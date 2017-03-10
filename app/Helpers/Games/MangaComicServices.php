<?php
namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper, Exception, Auth;

class MangaComicServices
{

    const TYPE = 12;   //num of the platform - FIX
    const PLATFORMID = 12;  // same with type - FIX
//    const VNG_PLATFORMID = 13;  // same with type - FIX
    const SECRETKEY = 'FACB085C7E22'; // Secret key for FPAY Platform - FIX
    const VNG_SECRETKEY = 'D852D910502A'; // Secret key for FPAY Platform - FIX
    const MTYPE = 1; // Money type - FIX
    const PAYTYPE = 1; // Pay type - FIX
    const API_LOGIN_URL = 'http://123.30.146.106/index.php';
    const API_PAYMENT_URL = 'http://123.30.146.106/pay.php';
    const API_ROLE_URL = 'http://123.30.146.106/queryRole.php';
    const API_RANK_URL = 'http://123.30.146.106/getRank.php';
    const APP_ID_SLG = 18903324;

    const RATE = 0.01;
    const RATE_REV = 100;

    const PREFIX = 'slg';

    private function getCorrectUID($uid)
    {
        return (strpos($uid, self::PREFIX) === false) ? self::PREFIX . $uid : $uid;
    }

    public function getLoginUrl($uid, $server_id)
    {
        //$uid = $this->getCorrectUID($uid);
        
        $arr = array(
            'openid' => $uid,
            'serialid' => $server_id,
            'time' => time(),
            'type' => self::TYPE,
            'platformID' => self::PLATFORMID,
        );

        $arr['token'] = self::generateToken($arr);

        return self::API_LOGIN_URL . '?' . http_build_query($arr);
    }

    private function generateToken(Array $arr)
    {
        $token = '';
        ksort($arr);    // sort array before hash

        foreach ($arr as $key => $value) {
            $token .= $key . '=' . $value;
        }

        $token = md5($token . self::SECRETKEY);

        return $token;
    }

    public function transfer($uid, $server_id, $order_id, $coin)
    {
        //$_uid = $this->getCorrectUID($uid);

        $log = new LogCoinTransfer;

        $log->request_time = date('Y-m-d H:i:s');
        $log->user_id = Auth::id();
        $log->server_id = $server_id;
        $log->app_id = self::APP_ID_SLG;
        $log->trans_info = 'Chuyển Coin vào Game.';
        $log->ip = CommonHelper::getClientIP();
        $log->trans_id = $order_id;
        $log->status = 0;
        $log->coin = $coin;

        $vnd = $coin * self::RATE_REV;

        $is_exist = $this->isExistingUser($uid, $server_id);
        if (!$is_exist) {
            $log->response_time = date('Y-m-d H:i:s');
            $log->response = 'Người chơi không tồn tại.';
            $log->save();

            return false;
        }

        $arr = array(
            'openid' => $uid,
            'type' => self::TYPE,
            'platformID' => self::PLATFORMID,
            'serialid' => $server_id,
            'time' => time(),
            'orderid' => $order_id,
            'paytype' => self::PAYTYPE,
            'mtype' => self::MTYPE,
            'money' => $vnd,
            'gold' => $coin
        );
        $token = self::generateToken($arr);

        $arr['token'] = $token;

        try {
            $payment_response = $this->post(self::API_PAYMENT_URL, $arr);
        } catch (\Exception $e) {
            $payment_response = 'cURL Error: ' . $e->getMessage();
        }

        $response = trim($payment_response);

        $log->response_time = date('Y-m-d H:i:s');

        if ($response == 'success') {
            $log->status = 1;
        }

        $log->response = $response;

        $log->save();

        return $response;
    }

    public function isExistingUser($uid, $server_id)
    {
        //$uid = $this->getCorrectUID($uid);

        $role = json_decode($this->getRole($uid, $server_id));

        if (is_object($role) && $role->level) {
            return $role->level;
        } else {
            return false;
        }
    }

    public function getRole($uid, $server_id)
    {
        //$uid = $this->getCorrectUID($uid);

        $arr = array(
            'openid' => $uid,
            'type' => self::TYPE,
            'platformID' => self::PLATFORMID,
            'serialid' => $server_id,
            'time' => time(),
        );

        ksort($arr);
        $arr['token'] = self::generateToken($arr);

        $response = '';

        try {
            $response = $this->post(self::API_ROLE_URL, $arr);
        } catch (Exception $e) {
            echo 'Transfer to game error: ', $e->getMessage(), "\n";
        }
        return $response;
    }

    private function post($url, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }
}