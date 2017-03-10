<?php
include_once ("Rediska.php");

class UtilLogRedis {
     static private $rediska;
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