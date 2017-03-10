<?phpnamespace App\Http\Controllers\Payment\Partner;include("libs_Banknet/Payment.php");include("libs_Banknet/QueryData.php");use Payment;use QueryData;class VisaMasterBanknet {    private static $vpc_Version = '2.0';    private static $vpc_Command = 'pay';    private static $vpc_AccessCode = 'B912B20F';    //product    //private static $vpc_AccessCode = '72AD46B6';  //test    private static $vpc_MerchTxnRef = '';    private static $vpc_Merchant = 'VINHXUAN';  //product    //private static $vpc_Merchant = 'test03051980';    //test    private static $vpc_OrderInfo = 'Nạp mu';    private static $vpc_Amount = '100000';    private static $vpc_Locale = 'vn';    private static $vpc_Currency = 'VND';    private static $vpc_ReturnURL = 'https://pay.slg.vn/topupcash/visacallback';    private static $vpc_ReturnURL_Mobile = 'https://pay.slg.vn/mtopupcash/visacallback';    private static $vpc_BackURL = 'https://pay.slg.vn/topupcash/bank';    private static $vpc_BackURL_Mobile = 'https://pay.slg.vn/mtopupcash/bank';    private static $secure_secret = '4794E37133C78EFD3A5D3C19C57F6D91';    //private static $secure_secret = '0ECDD6D43A5BF997EC20C18600441E56';   //test    //private static $virtual_paymentUrl = 'http://payment.smartlink.com.vn/gateway/vpcpay.do';    private static $virtual_paymentUrl = 'https://migs.mastercard.com.au/vpcpay';    public static function redirectToBanknet($arr, $is_mobile = FALSE) {        $arr['vpc_AccessCode'] = self::$vpc_AccessCode;        $arr['vpc_BackURL'] = $is_mobile ? self::$vpc_BackURL_Mobile : self::$vpc_BackURL;        $arr['vpc_Command'] = self::$vpc_Command;        $arr['vpc_Currency'] = self::$vpc_Currency;        $arr['vpc_Locale'] = self::$vpc_Locale;        $arr['vpc_Merchant'] = self::$vpc_Merchant;        $arr['vpc_OrderInfo'] = 'Nap ' . $arr['vpc_Amount'] . ' VND tien vao vi-' . $arr['game'];        $arr['vpc_ReturnURL'] = $is_mobile ? self::$vpc_ReturnURL_Mobile : self::$vpc_ReturnURL;        $arr['vpc_Version'] = self::$vpc_Version;        unset($arr['game']);        $payment = new Payment();        $payment->setSecureSecret(self::$secure_secret);        $payment->setVirtualPaymentUrl(self::$virtual_paymentUrl);        $payment->redirect($arr);        exit();    }    public static function checkVisaCardBanknetTransaction($arr) {        /* test */        //https://pay.slg.vn/topupcash/visacallback?vpc_3DSECI=05&vpc_3DSXID=7Vd4o2Azx4VCk9rq8hV%2FCfddzic%3D&vpc_3DSenrolled=Y&vpc_3DSstatus=Y&vpc_AVSRequestCode=Z&vpc_AVSResultCode=Unsupported&vpc_AcqAVSRespCode=Unsupported&vpc_AcqCSCRespCode=M&vpc_AcqResponseCode=00&vpc_Amount=10000&vpc_AuthorizeId=008068&vpc_BatchNo=20151112&vpc_CSCResultCode=M&vpc_Card=VC&vpc_Command=pay&vpc_Currency=VND&vpc_Locale=vn&vpc_MerchTxnRef=SML_999999_1447310190_627085817&vpc_Merchant=VINHXUAN&vpc_Message=Approved&vpc_OrderInfo=Nap+10000+VND+tien+vao+&vpc_ReceiptNo=531617656962&vpc_RiskOverallResult=ACC&vpc_SecureHash=91F1A8FE94344470E3D5871D0FF07DFE&vpc_TransactionNo=1100000003&vpc_TxnResponseCode=0&vpc_VerSecurityLevel=05&vpc_VerStatus=Y&vpc_VerToken=AAABAxNVMwAAAAAAEFUzAAAAAAA%3D&vpc_VerType=3DS&vpc_Version=2.0        $SECURE_SECRET = '4794E37133C78EFD3A5D3C19C57F6D91';        $vpc_Txn_Secure_Hash = $arr['vpc_SecureHash'];        unset($arr['vpc_SecureHash']);        dd($arr);        // set a flag to indicate if hash has been validated        $errorExists = false;        if (strlen($SECURE_SECRET) > 0 && $arr['vpc_TxnResponseCode'] != "7" && $arr['vpc_TxnResponseCode'] != "No Value Returned") {            $md5HashData = $SECURE_SECRET;            // sort all the incoming vpc response fields and leave out any with no value            foreach ($_GET as $key => $value) {                if ($key != "vpc_SecureHash" or strlen($value) > 0) {                    $md5HashData .= $value;                }            }            dd(md5($md5HashData));            if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData))) {                // Secure Hash validation succeeded, add a data field to be displayed                // later.                $hashValidated = 1;            } else {                // Secure Hash validation failed, add a data field to be displayed                // later.                $hashValidated = 2;                $errorExists = true;            }        } else {            // Secure Hash was not validated, add a data field to be displayed later.            $hashValidated = $hashValidated = 3;        }        //dd($hashValidated);        dd ($hashValidated);    }}?>