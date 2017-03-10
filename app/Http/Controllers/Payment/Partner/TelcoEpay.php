<?php

namespace App\Http\Controllers\Payment\Partner;

include 'lib_charging/Entries.php';
include 'lib_charging/Charging_Order.php';

use SoapClient;
use CardCharging;
use charging_order;
use CardChargingResponse;
use Illuminate\Http\RedirectResponse;
use nusoap_client;
use cache;

class TelcoEpay {

    private static $m_PartnerID = "HQ004";
    private static $m_MPIN = "yvlsrrtqn";
    private static $m_UserName = "HQ004";
    private static $m_Pass = "htwkbdoir";
    private static $m_PartnerCode = "00711";
    private static $webservice = "http://charging-service.megapay.net.vn/CardChargingGW_V2.0/services/Services?wsdl";

    public function makeRequest() {
        
    }

    public static function callService($uid, $serial, $pin, $provider, $request_id) {

        //echo $uid . ' ' . $serial . ' ' . $pin . ' ' . $provider;
        $m_PartnerID = self::$m_PartnerID;
        $m_MPIN = self::$m_MPIN;
        $m_UserName = self::$m_UserName;
        $m_Pass = self::$m_Pass;
        $m_PartnerCode = self::$m_PartnerCode;
        $m_Target = 'u' . rand(10000000, 99999999);
        $webservice = self::$webservice;

        try {
            $soapClient = new SoapClient(null, array('location' => $webservice, 'uri' => "http://113.161.78.134/VNPTEPAY/"));
            //$soapClient = new nusoap_client($webservice, 'wsdl', '', '', '', '');
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        $CardCharging = new CardCharging();
        $CardCharging->m_UserName = $m_UserName;
        $CardCharging->m_PartnerID = $m_PartnerID;
        $CardCharging->m_MPIN = $m_MPIN;
        $CardCharging->m_Target = $m_Target;
        $CardCharging->m_Card_DATA = $serial . ":" . $pin . ":" . "0" . ":" . $provider;
        $CardCharging->m_SessionID = "";
        $CardCharging->m_Pass = $m_Pass;
        $CardCharging->soapClient = $soapClient;

        //$transid = $m_PartnerCode.'_'.$user->uid.'_'.generateRequestID();//gen transaction id
        if (empty($request_id)) {
            $transid = $m_PartnerCode . '_' . $uid . '_' . uniqid();
        } else {
            $transid = $request_id;
        }
        $CardCharging->m_TransID = $transid;

        $CardChargingResponse = new CardChargingResponse();
        $CardChargingResponse = $CardCharging->CardCharging_();
        //dd($CardChargingResponse);
        return $CardChargingResponse;
    }

    public static function demo() {
       
    }

}

?>