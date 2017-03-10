<?php

namespace App\Libraries\ZingCredits;

use App\Libraries\ZingCredits\ZC2_EncryptFactory;
use App\Libraries\ZingCredits\ZC2_EncryptIF;
use App\Libraries\ZingCredits\ZC2_BillingData;
use App\Libraries\ZingCredits\ZC2_BalanceData;
use App\Libraries\ZingCredits\ZC2_CallbackResultData;

class ZCypher2Lib {
    
    static $TYPE_ENCRYPT = "1";
    static $EXPIRED_TIME = 300; //5 mins
    
    ////////////// billing //////////
    /**
    * Function to encode billing data
    * @param string $key1 of app register with ZingCredits
    * @param ZC2_BillingData $data
    * return String encoded data
    */
    public static function encodeDataForBilling($key1, ZC2_BillingData $data) {
        $key = self::getKeyForBill($key1);

        $holder = array();
        $holder['' . rand(0, 1000) . '__'] = rand(0, 100000);
        $holder['uid'] = strval($data->uid);
        $holder['billNo'] = strval($data->billNo);
        $holder['itemIDs'] = strval($data->itemIDs);
        $holder['itemNames'] = strval($data->itemNames);
        $holder['itemQuantities'] = strval($data->itemQuantities);
        $holder['itemPrices'] = strval($data->itemPrices);
        $holder['amount'] = strval($data->amount);
        $holder['localtime'] = strval($data->localUnixTimeStampInSecs);
        $holder['__ts'] = strval(time());

        $val = json_encode($holder);        

        return self::$TYPE_ENCRYPT . "|" . ZC2_EncryptFactory::factory(self::$TYPE_ENCRYPT)->encode($key, $val);
    }

    /**
    * function to decode
    * @param string $key1 of app register with ZingCredits
    * @param string $encodedData
    * @param ZC2_BillingData $dataReturn
    * @param type $expireInSecs : default expire in 5mins
    * return 0 if success, -1 if failed, -2 if expired
    */
    public static function decodeDataForBilling($key1, $encodedData, ZC2_BillingData &$dataReturn) {
        $expireInSecs = self::$EXPIRED_TIME;
        $ret = -1;
        if (empty($encodedData)) {
            return $ret;
        }
        
        $key = self::getKeyForBill($key1);
        try {
            
            $pos = strpos($encodedData, "|");            
            $type = substr($encodedData, 0, $pos);            
            $encodedData = substr($encodedData, $pos + 1);
            
            if ($type != ZC2_EncryptFactory::$AES256) {
                $type = self::$TYPE_ENCRYPT;
            }

            $val = ZC2_EncryptFactory::factory($type)->decode($key, $encodedData);

            if ($val != null && !empty($val)) {

                $obj = json_decode($val, true);

                if (!is_array($obj)) {
                    return -1;
                }

                if (!array_key_exists("__ts", $obj)) {
                    return -1;
                }

                $__ts = intval($obj['__ts']);
                if ($__ts <= 0)
                    return -1;

                $now = time();

                if ($expireInSecs > 0 && ($now > ($__ts + $expireInSecs))) {
                    return -2; //expired					
                }

                unset($obj['__ts']);

                if ($dataReturn == null) {
                    $dataReturn = new ZC2_BillingData();
                }

                $dataReturn->uid = $obj['uid'];
                $dataReturn->billNo = $obj['billNo'];
                $dataReturn->itemIDs = $obj['itemIDs'];
                $dataReturn->itemNames = $obj['itemNames'];
                $dataReturn->itemQuantities = $obj['itemQuantities'];
                $dataReturn->itemPrices = $obj['itemPrices'];
                $dataReturn->amount = $obj['amount'];
                $dataReturn->localUnixTimeStampInSecs = $obj['localtime'];

                $ret = 0;
            }
        } catch (Exception $ex) {
            $ret = -1;
        }
        return $ret;
    }
    
    ////////// balance /////////
    /**
    * Function to encode billing data
    * @param string $key1 of app register with ZingCredits
    * @param ZC2_BalanceData $data
    * return String encoded data
    */
    public static function encodeDataForBalance($key1, ZC2_BalanceData $data) {
        $key = self::getKeyForBal($key1);

        $holder = array();
        $holder['' . rand(0, 1000) . '__'] = rand(0, 100000);
        $holder['uid'] = strval($data->uid);       
        $holder['__ts'] = strval(time());

        $val = json_encode($holder);        

        return self::$TYPE_ENCRYPT . "|" . ZC2_EncryptFactory::factory(self::$TYPE_ENCRYPT)->encode($key, $val);
    }
    
    /////////// callbackResult /////////
    
    /**
    * function to decode
    * @param string $key2 of app register with ZingCredits
    * @param string $encodedData
    * @param ZC2_CallbackResultData $dataReturn
    * @param type $expireInSecs : default expire in 5mins
    * return 0 if success, -1 if failed, -2 if expired
    */
    public static function decodeDataForCallbackResult($key2, $encodedData, ZC2_CallbackResultData &$dataReturn) {
        $expireInSecs = self::$EXPIRED_TIME;
        $ret = -1;
        if (empty($encodedData)) {
            return $ret;
        }
        
        $key = $key2;
        try {
            $pos = strpos($encodedData, "|");
            $type = substr($encodedData, 0, $pos);    
            $encodedData = substr($encodedData, $pos + 1);
            
            
            if ($type != ZC2_EncryptFactory::$AES256) {
                $type = self::$TYPE_ENCRYPT;
            }

            $val = ZC2_EncryptFactory::factory($type)->decode($key, $encodedData);

            if ($val != null && !empty($val)) {

                $obj = json_decode($val, true);

                if (!is_array($obj)) {
                    return -1;
                }

                if (!array_key_exists("__ts", $obj)) {
                    return -1;
                }

                $__ts = intval($obj['__ts']);
                if ($__ts <= 0)
                    return -1;

                $now = time();

                if ($expireInSecs > 0 && ($now > ($__ts + $expireInSecs))) {
                    return -2; //expired					
                }

                unset($obj['__ts']);

                if ($dataReturn == null) {
                    $dataReturn = new ZC2_CallbackResultData();
                }

                $dataReturn->uid = $obj['uid'];
                $dataReturn->billNo = $obj['billNo'];
                $dataReturn->itemIDs = $obj['itemIDs'];
                $dataReturn->itemNames = $obj['itemNames'];
                $dataReturn->itemQuantities = $obj['itemQuantities'];
                $dataReturn->itemPrices = $obj['itemPrices'];
                $dataReturn->amount = $obj['amount'];
                $dataReturn->localUnixTimeStampInSecs = $obj['localtime'];
				$dataReturn->txID_ZingCredits = $obj['txID_ZingCredits'];

                $ret = 0;
            }
        } catch (Exception $ex) {
            $ret = -1;
        }
        return $ret;
    }

    //////////////////////////////////////////////
    private static function getKeyForBill($key) {
            return "bil" . $key;
    }

    private static function getKeyForBal($key) {
            return "bal" . $key;
    }
}
