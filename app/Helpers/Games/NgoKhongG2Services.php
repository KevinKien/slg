<?php

namespace App\Helpers\Games;

use App\Models\LogCoinTransfer;
use CommonHelper;

class NgoKhongG2Services {

    const TYPE = 12;   //num of the platform - FIX

    private function getCorrectUID($uid) {
        return 'getCorrectUID';
    }

    public function getLoginUrl($uid, $server_id) {
        return 'getLoginUrl';
    }

    private function generateToken(Array $arr) {
        return 'generateToken';
    }

    public function transfer($uid, $server_id, $order_id, $coin) {
        return TRUE;
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

}
