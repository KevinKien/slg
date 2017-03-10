<?php

namespace App\Http\Controllers\Payment\Partner;

include("libs_Banknet/Payment.php");
include("libs_Banknet/QueryData.php");

use Payment;
use QueryData;

class AtmCardBanknet {

    private static $vpc_Version = '1.1';
    private static $vpc_Command = 'pay';
    //private static $vpc_AccessCode = 'F1P2A3Y4';
    private static $vpc_AccessCode = 'V1I2N3H4X4U5A6N7'; //product
    //private static $vpc_AccessCode = 'ECAFAB'; //test
    private static $vpc_MerchTxnRef = '';
    private static $vpc_Merchant = 'VINHXUAN'; // product
    //private static $vpc_Merchant = 'SMLTEST'; // test
    private static $vpc_OrderInfo = 'Nạp mua coin';
    private static $vpc_Amount = '100000';
    private static $vpc_Locale = 'vn';
    private static $vpc_Currency = 'VND';
    private static $vpc_ReturnURL = 'https://pay.slg.vn/topupcash/atmcallback';
    private static $vpc_ReturnURL_Mobile = 'https://pay.slg.vn/mtopupcash/atmcallback';
    private static $vpc_BackURL = 'https://pay.slg.vn/topupcash/bank';
    private static $vpc_BackURL_Mobile = 'https://pay.slg.vn/mtopupcash/bank';
    //private static $secure_secret = '7C0C53494D208BD772CC9E2B20AB5F0C';
    private static $secure_secret = 'E4FC53C1DFFB60DC791220E403890EC4';  //product
    //private static $secure_secret = '198BE3F2E8C75A53F38C1C4A5B6DBA27';  //test
    private static $virtual_paymentUrl = 'https://payment.napas.com.vn/vpcpay.do';    // product
    //private static $virtual_paymentUrl = 'http://paymentcert.napas.com.vn/vpcpay.do'; // test

    public static function redirectToBanknet($arr, $is_mobile = FALSE) {
        $arr['vpc_AccessCode'] = self::$vpc_AccessCode;
        $arr['vpc_BackURL'] = $is_mobile ? self::$vpc_BackURL_Mobile : self::$vpc_BackURL;
        $arr['vpc_Command'] = self::$vpc_Command;
        $arr['vpc_Currency'] = self::$vpc_Currency;
        $arr['vpc_Locale'] = self::$vpc_Locale;
        $arr['vpc_Merchant'] = self::$vpc_Merchant;
        $arr['vpc_OrderInfo'] = 'Nap ' . $arr['vpc_Amount'] . ' VND tien vao vi-' . $arr['game'];
        $arr['vpc_ReturnURL'] = $is_mobile ? self::$vpc_ReturnURL_Mobile : self::$vpc_ReturnURL;
        $arr['vpc_Version'] = self::$vpc_Version;
        $arr['vpc_Amount'] = $arr['vpc_Amount'] * 100;

        unset($arr['game']);

        $payment = new Payment();
        $payment->setSecureSecret(self::$secure_secret);
        $payment->setVirtualPaymentUrl(self::$virtual_paymentUrl);

        $payment->redirectATM($arr);
        exit();
    }

    public static function checkAtmCardBanknetTransaction($vpc_MerchTxnRef) {
        $data = array();
        $data['vpc_Version'] = '1.1';
        $data['vpc_Merchant'] = self::$vpc_Merchant;
        $data['vpc_AccessCode'] = self::$vpc_AccessCode;
        $data['vpc_Command'] = 'queryDR';
        $data['vpc_MerchTxnRef'] = $vpc_MerchTxnRef;

        $payment = new Payment();
        $payment->setSecureSecret(self::$secure_secret);
        //$payment->setVirtualPaymentUrl("http://paymentcert.napas.com.vn:2468/vpcdps");
        $payment->setVirtualPaymentUrl("https://payment.napas.com.vn/vpcdps");

        $queryData = $payment->getQueryResult($data);
        // Example data response
//        QueryData {#456 ▼
//            +vpc_DRExists: "Y"
//            +vpc_FoundMultipleDRs: "N"
//            +vpc_TxnResponsddeCode: "21"
//            +vpc_Message: "Transaction SML_1000011_1443431369_1526023178 is failed"
//            +vpc_SecureHash: "DA92CA94EFD40BF0BD431C721F345235"
//          }
        //$queryData->vpc_TxnResponseCode = '00';
        //$queryData->vpc_Amount = '100000';
        return($queryData);
    }
}
?>