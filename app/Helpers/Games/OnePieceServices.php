<?php
namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper, Exception;
use App\Models\LogBuyItemZing;

class OnePieceServices
{

    const TYPE = 12;   //num of the platform - FIX* Type for op.slg.vn
    const PLATFORMID = 12;  // same with type - FIX*
    const SECRETKEY = 'A8828B45C265'; // Secret key for FPAY Platform - FIX *  Key cho op.slg.vn
    const TYPE_ZING = 13;  // same with type - FIX For manga comic VNG
    const SECRETKEY_ZING = '224F728A1775'; // Secret key for FPAY Platform - FIX For manga comic VNG
    //const OP_SECRETKEY = 'CE385F28E7C0'; // Secret key for FPAY Platform - FIX * Key cho facebook op
    //const TYPE = 22;   //num of the platform - FIX* Type for facebook op

    const MTYPE = 1; // Money type - FIX For manga comic VNG
    const PAYTYPE = 1; // Pay type - FIX For manga comic VNG
    const CP_ID_ZING = 300000184;

    const API_LOGIN_URL = 'http://s00.op.slg.vn/index.php';
    const API_PAYMENT_URL = 'http://s00.op.slg.vn/pay.php';
    const API_ROLE_URL = 'http://s00.op.slg.vn/queryRole.php';
    const API_RANK_URL = 'http://s00.op.slg.vn/getRank.php';
    const APP_ID_SLG = 17054245;

    const RATE = 0.01;
    const RATE_REV = 100;

    const PREFIX = 'slg';

    private function getCorrectUID($uid)
    {
        return (strpos($uid, self::PREFIX) === false) ? self::PREFIX . $uid : $uid;
    }

    private static function getMeger($server_id)
    {
        $meger = 0;
        if ($server_id >= 1 && $server_id <= 4) {
            $meger = "1";
        }
        if ($server_id >= 5 && $server_id <= 10) {
            $meger = "2";
        }
        if ($server_id >= 11 && $server_id <= 16) {
            $meger = "3";
        }
        if ($server_id >= 17 && $server_id <= 19) {
            $meger = "4";
        }
        if ($server_id >= 20 && $server_id <= 22) {
            $meger = "5";
        }
        if ($server_id >= 23 && $server_id <= 25) {
            $meger = "6";
        }

        return $meger;
    }

    public function getLoginUrl($uid, $server_id, $partner = false)
    {
        $uid = $this->getCorrectUID($uid);

        if (!$partner) {
            $user = \Auth::user();
            if ($user->fid > 0) {
                $uid = $user->fid;
            }
        }

        $type = $partner ? self::TYPE_ZING : self::TYPE;

        $arr = array(
            'openid' => $uid,
            'serialid' => $server_id,
            'time' => time(),
            'type' => $type,
            'meger' => self::getMeger($server_id),
        );

        $arr['token'] = self::generateToken($arr, $partner);

        return self::API_LOGIN_URL . '?' . http_build_query($arr);
    }

    private function generateToken(Array $arr, $partner = false)
    {
        $str = '';
        ksort($arr);    // sort array before hash

        foreach ($arr as $key => $value) {
            $str .= $key . '=' . $value;
        }

        $secret_key = $partner ? self::SECRETKEY_ZING : self::SECRETKEY;

        return $token = md5($str . $secret_key);
    }

    public function transfer($uid, $server_id, $order_id, $coin)
    {
        $user = \Auth::user();

        if ($user->fid > 0) {
            $_uid = $user->fid;
        } else {
            $_uid = $this->getCorrectUID($uid);
        }

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

        $vnd = $coin * self::RATE_REV;

        $is_exist = $this->isExistingUser($_uid, $server_id);
        if (!$is_exist) {
            $log->response_time = date('Y-m-d H:i:s');
            $log->response = 'Người chơi không tồn tại.';
            $log->save();

            return false;
        }

        $arr = array(
            'openid' => $_uid,
            'type' => self::TYPE,
            'serialid' => $server_id,
            'time' => time(),
            'orderid' => $order_id,
            'paytype' => self::PAYTYPE,
            'mtype' => self::MTYPE,
            'money' => $vnd,
            'gold' => $coin,
            'meger' => self::getMeger($server_id)
        );

        $arr['token'] = self::generateToken($arr);

        try {
            $_response = $this->post(self::API_PAYMENT_URL . '?' . http_build_query($arr));
            $payment_response = str_replace(array('\ufeff', 'ï»¿'), '', utf8_encode($_response));
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

    public function chargeByZing($uid, $server_id, $order_id, $coin, $order_info = 'Coin Charging')
    {
        $_uid = $this->getCorrectUID($uid);

        $log = new LogCoinTransfer;

        $log->request_time = date('Y-m-d H:i:s');
        $log->user_id = $uid;
        $log->server_id = $server_id;
        $log->app_id = self::APP_ID_SLG;
        $log->trans_info = $order_info;
        $log->ip = CommonHelper::getClientIP();
        $log->trans_id = $order_id;
        $log->status = 0;
        $log->coin = $coin;

        $log_zing = new LogBuyItemZing;

        $log_zing->cpid = self::CP_ID_ZING;
        $log_zing->request_time = date('Y-m-d H:i:s');
        $log_zing->userid = $uid;
        $log_zing->server_id = $server_id;
        $log_zing->channel_id = 'zing';
        $log_zing->zing_order_info = $order_info;
        $log_zing->order_id = $order_id;
        $log_zing->status = 0;

        $vnd = $coin * self::RATE_REV;

        $log_zing->item_price = $vnd;

        $role = json_decode($this->getRole($_uid, $server_id, true), true);

        if (empty($role) || !isset($role['level'])) {
            $log->response_time = date('Y-m-d H:i:s');
            $log->response = 'Người chơi không tồn tại.';
            $log->save();

            $log_zing->response_time = date('Y-m-d H:i:s');
            $log_zing->save();

            return false;
        }

        $arr = [
            'openid' => $_uid,
            'type' => self::TYPE_ZING,
            'serialid' => $server_id,
            'time' => time(),
            'orderid' => $order_id,
            'paytype' => self::PAYTYPE,
            'mtype' => self::MTYPE,
            'money' => $vnd,
            'gold' => $coin,
            'meger' => self::getMeger($server_id)
        ];

        $arr['token'] = self::generateToken($arr, true);

        $url = self::API_PAYMENT_URL . '?' . http_build_query($arr);

        $response = CommonHelper::cURLWithRetry('get', $url, 3);

        if ($response !== false) {
            $response = trim(str_replace(array('\ufeff', 'ï»¿'), '', utf8_encode($response)));
        }

        $log->response_time = date('Y-m-d H:i:s');
        $log_zing->response_time = date('Y-m-d H:i:s');

        if ($response == 'success') {
            $log->status = 1;
            $log_zing->status = 3;
        }

        $log->response = $response;

        //$log->save();
        $log_zing->save();

        return $response;
    }

    public function getTopPlayers($server_id, $rankType, $partner = false, $limit = 20)
    {
        $type = $partner ? self::TYPE_ZING : self::TYPE;

        $arr = [
            'type' => $type,
            'serialid' => $server_id,
            'time' => time(),
            'rankType' => $rankType,
            'rankCount' => $limit,
        ];

        $arr['token'] = self::generateToken($arr, $partner);

        $url = self::API_RANK_URL . '?' . http_build_query($arr);

        return CommonHelper::cURLWithRetry('get', $url, 3);
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

    public function getRole($uid, $server_id, $partner = false)
    {
        //$uid = $this->getCorrectUID($uid);

        $type = $partner ? self::TYPE_ZING : self::TYPE;

        $arr = array(
            'openid' => $uid,
            'type' => $type,
            'serialid' => $server_id,
            'time' => time(),
//            'meger' => self::getMeger($server_id),
        );

        ksort($arr);
        $arr['token'] = self::generateToken($arr, $partner);

        $url = self::API_ROLE_URL . '?' . http_build_query($arr);

        return CommonHelper::cURLWithRetry('get', $url, 3);
    }

    private function post($url, $data = false)
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