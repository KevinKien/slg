<?php
/**
 * Created by PhpStorm.
 * User: vuong
 * Date: 11/1/2016
 * Time: 3:09 PM
 */

namespace App\Libraries\ZingSDK;

use Exception;

class ZingMeApiException extends Exception {

    private $_err_code = 0;
    private $_err_type = "";
    private $_err_msg = "";

    public function __construct($err_code, $err_type, $err_msg) {
        $this->_err_code = $err_code;
        $this->_err_type = $err_type;
        $this->_err_msg = $err_msg;

        parent::__construct($this->_err_msg, $this->_err_code);
    }

    public function getErrType() {
        return $this->_err_type;
    }

    public function getErrMsg() {
        return $this->_err_msg;
    }

    public function __toString() {
        $str = $this->_err_type . ': ';
        if ($this->_err_code != 0) {
            $str .= $this->_err_code . ': ';
        }
        return $str . $this->_err_msg;
    }

}