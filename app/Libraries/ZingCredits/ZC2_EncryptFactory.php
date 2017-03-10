<?php

namespace App\Libraries\ZingCredits;

use App\Libraries\ZingCredits\ZC2_AES256;

class ZC2_EncryptFactory {

    public static $AES256 = "1";
    
    /**
     * 
     * @param type $type
     * @return EncryptIF
     */
    public static function factory($type) {
        if ($type == self::$AES256) {
            return ZC2_AES256::getInstance();
        } else {
            return ZC2_AES256::getInstance();
        }
    }

}