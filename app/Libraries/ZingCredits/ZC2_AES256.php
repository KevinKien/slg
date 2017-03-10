<?php

namespace App\Libraries\ZingCredits;

use App\Libraries\ZingCredits\ZC2_EncryptIF;

class ZC2_AES256 implements ZC2_EncryptIF {

    private static $instance = null;

    /**
     * 
     * @return ZC2_EncryptIF
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    public function encode($key, $value) {        
        $key = md5($key);
        $_key = $this->_getKey($key);
        $_vector = $this->_getVector($key);        
        return $this->encryptAES256($_key, $_vector, $value);
    }

    public function decode($key, $cipherText) {
        //var_dump("_ori_key=" . $key);
        $key = md5($key);
        $_key = $this->_getKey($key);        
        $_vector = $this->_getVector($key); 
        //var_dump("_key=" . $_key);
        //var_dump("\n");
        //var_dump("_vector=" . $_vector);   
        return $this->decryptAES256($_key, $_vector, $cipherText);
    }

    private function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    private function base64UrlEncode($input) {
        return strtr(base64_encode($input), '+/', '-_');
    }

    private function decryptAES256($key, $vector, $text) {

        $text = $this->base64UrlDecode($text);


        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');        

        mcrypt_generic_init($td, $key, $vector);
        $decrypted_string = mdecrypt_generic($td, $text);
        
        $decrypted_string = $this->pkcs5_unpad($decrypted_string);

//        $dec_s = strlen($decrypted_string);
//        $padding = ord($decrypted_string[$dec_s - 1]);
//        $decrypted_string = substr($decrypted_string, 0, -$padding);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        // Return the encrypt text in 64 bits code
        return $decrypted_string;
    }

    private function encryptAES256($key, $vector, $text) {
        
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC); 
        $text = $this->pkcs5_pad($text, $size); 

        mcrypt_generic_init($td, $key, $vector);
        $encrypt64 = mcrypt_generic($td, $text);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        // Return the encrypt text in 64 bits code
        return $this->base64UrlEncode($encrypt64);
    }

    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    private function _getKey($key) {
        if ($key == null)
            return "";
        if (strlen($key) >= 32) {
            return substr($key, 0, 32);
        } else
            return $key;
    }

    private function _getVector($key) {
        if ($key == null)
            return "";
        if (strlen($key) >= 16) {
            return substr($key, strlen($key) - 16, strlen($key));
        } else
            return $key;
    }

}
