<?php

include_once ("Rediska.php");

class UtilLogRedis {

    static private $rediska;

    static public function getInstance() {
        $options = array(
            'namespace' => 'Application_',
            'servers' => array(
                array('host' => '192.168.1.194', 'port' => 6379),
            )
        );
        // connect to redis
        self::$rediska = new Rediska($options);
    }

    static public function getKey($key_redis) {
        self::getInstance();
        if (!empty($key_redis)) {
            $obj = new Rediska_Key($key_redis);
            $data = $obj->getValue();

            return $data;
        }
        return FALSE;
    }

    static public function setKey($key_redis, $value, $time = 0) {
        self::getInstance();
        if (!empty($key_redis)) {
            $obj = new Rediska_Key($key_redis);
            $obj->setValue($value);
            if (!empty($time)) {
                $obj->expire($time);
            }
            return $obj->getvalue();
        }
        return FALSE;
    }

    static public function getKeys($pattern) {
        //$rediska = new Rediska($options);
        self::getInstance();
        if (!empty($pattern)) {
            $obj = self::$rediska->getKeysByPattern($pattern);
            return $obj;
        }
        return FALSE;
    }

    static public function increment($key, $amount = 1) {
        self::getInstance();
        if (!empty($key)) {
            $obj = self::$rediska->increment($key, $amount);
            return $obj;
        }
        return FALSE;
    }
     static public function getMKeys(Array $arr_keys){
        self::getInstance();
        if(!is_array($arr_keys)){
            $data = array();
            foreach($arr_keys as $key => $value){
                $data[] = self::getKey($value);
            }
            return $data;
        }
        return FALSE;
    }
   

}

?>