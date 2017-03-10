<?php

namespace App\Http\Controllers\Payment\Partner;

class AtmVisaOnePay {

    public static function getUrlService($traninfo, $card_type) {

        $url_service = '';

        if ($card_type == 'atm') {
            // card_type = atm noi dia
            $url_service = 'https://mtf.onepay.vn/onecomm-pay/vpc.op'; //test
            //$url_service = 'https://onepay.vn/onecomm-pay/vpc.op'; //that
        } elseif ($card_type == 'visa') {
            // card_type = 2 visa
            $url_service = 'https://mtf.onepay.vn/vpcpay/vpcpay.op'; //set your dot net web service url
            //$url_service = 'https://onepay.vn/vpcpay/vpcpay.op'; //set your dot net web service url
        }

        return $url_service;
    }

    public static function createSignature($traninfo, $card_type) {
        $Signature = '';
        $appendAmp = 0;
        $stringHashData = '';

        if ($card_type == 'atm') {
            $arr_params = array(
                "Title" => 'SLG Payment',
                "vpc_AccessCode" => 'D67342C2',
                "vpc_Amount" => '100000',
                "vpc_Command" => 'pay',
                "vpc_Currency" => 'VND',
                "vpc_Customer_Email" => 'support@onepay.vn',
                "vpc_Customer_Id" => 'thanhvt',
                "vpc_Customer_Phone" => '840904280949',
                "vpc_Locale" => 'VN',
                "vpc_MerchTxnRef" => '20131212231316277463475',
                "vpc_Merchant" => 'ONEPAY',
                "vpc_OrderInfo" => 'Nap Gcoin',
                "vpc_ReturnURL" => 'http://pay.slg.vn/topupcash/atmcallback',
                "vpc_SHIP_City" => 'Ha Noi',
                "vpc_SHIP_Country" => 'Viet Nam',
                "vpc_SHIP_Provice" => 'Hoan Kiem',
                "vpc_SHIP_Street01" => '39A Ngo Quyen',
                "vpc_TicketNo" => '192.168.0.68',
                "vpc_Version" => '2'
            );

            $SECURE_SECRET = 'A3EFDFABA8653DF2342E8DAC29B51AF0'; // Bi?n n�y du?c Soha Payment cung c?p cho merchant site		
            $arr_params["vpc_MerchTxnRef"] = $traninfo['order_code'];
            $arr_params["vpc_OrderInfo"] = $traninfo['order_code'];
            $arr_params["vpc_Amount"] = $traninfo['money_select'] * 100;
            $arr_params["vpc_TicketNo"] = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $vpcURL = self::getUrlService($traninfo, $card_type);
            $vpcURL .= "?";

            foreach ($arr_params as $key => $value) {
                if (strlen($value) > 0) {
                    if ($appendAmp == 0) {
                        $vpcURL .= urlencode($key) . '=' . urlencode($value);
                        $appendAmp = 1;
                    } else {
                        $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                    }
                    if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                        $stringHashData .= $key . "=" . $value . "&";
                    }
                }
            }

            $stringHashData = rtrim($stringHashData, "&");

            if (strlen($SECURE_SECRET) > 0) {
                $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)));
            }
        } else if ($card_type == 'visa') {
            $arr_params = array(
                "AVS_City" => 'Hanoi',
                "AVS_PostCode" => '10000',
                "AVS_StateProv" => 'Hoan Kiem',
                "AVS_Street01" => '194 Tran Quang Khai',
                "AgainLink" => 'http%3A%2F%2Fpay.slg.vn%2Ftopupcash',
                "Title" => 'SLG Payment',
                "display" => '',
                "vpc_AccessCode" => '6BEB2546',
                "vpc_Amount" => '100000',
                "vpc_Command" => 'pay',
                //"vpc_Currency" =>'VND',
                "vpc_Customer_Email" => 'support@onepay.vn',
                "vpc_Customer_Id" => 'thanhvt',
                "vpc_Customer_Phone" => '840904280949',
                "vpc_Locale" => 'VN',
                "vpc_MerchTxnRef" => '20131212231316277463475',
                "vpc_Merchant" => 'TESTONEPAY',
                "vpc_OrderInfo" => 'Nap Gcoin',
                "vpc_ReturnURL" => 'http://pay.slg.vn/topupcash/visacallback',
                "vpc_SHIP_City" => 'Ha Noi',
                "vpc_SHIP_Country" => 'Viet Nam',
                "vpc_SHIP_Provice" => 'Hoan Kiem',
                "vpc_SHIP_Street01" => '39A Ngo Quyen',
                "vpc_TicketNo" => '192.168.0.68',
                "vpc_Version" => '2'
            );

            $SECURE_SECRET = '6D0870CDE5F24F34F3915FB0045120DB'; // Bi?n n�y du?c Soha Payment cung c?p cho merchant site		
            $arr_params["vpc_MerchTxnRef"] = $traninfo['order_code'];
            $arr_params["vpc_OrderInfo"] = $traninfo['order_code'];
            $arr_params["vpc_Amount"] = $traninfo['money_select'] * 100;
            $arr_params["vpc_TicketNo"] = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $vpcURL = self::getUrlService($traninfo, $card_type);
            $vpcURL .= "?";

            foreach ($arr_params as $key => $value) {

                if (strlen($value) > 0) {
                    if ($appendAmp == 0) {
                        $vpcURL .= urlencode($key) . '=' . urlencode($value);
                        $appendAmp = 1;
                    } else {
                        $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                    }
                    if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                        $stringHashData .= $key . "=" . $value . "&";
                    }
                }
            }

            $stringHashData = rtrim($stringHashData, "&");

            if (strlen($SECURE_SECRET) > 0) {
                $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)));
            }
        }

        return $vpcURL;
    }

    public static function createArraytoCallService($traninfo, $Signature) {

        $arr = array();

        // card_type = 1 for gate
        if ($traninfo['card_type'] == 1 || $traninfo['card_type'] == 4) {
            // card_type = 1 for gate OR  card_type = 4 for viettel
            $arr = array(
                'MerchantID' => $this->MerchantID
                , 'UserName' => $traninfo['username']
                , 'Serial' => $traninfo['card_seri']
                , 'PIN' => $traninfo['card_code']
                , 'Signature' => $Signature
            );
        } else if ($traninfo['card_type'] == 2 || $traninfo['card_type'] == 3) {
            // card_type = 2 for mobiphone OR  card_type = 3 for vinaphone
            $arr = array(
                'MerchantID' => $this->MerchantID
                , 'PIN' => $traninfo['card_code']
                , 'Username' => $traninfo['username']
                , 'Email' => $traninfo['order_email']
                , 'Mobile' => $traninfo['order_mobile']
                , 'Serial' => $traninfo['card_seri']
                , 'Signature' => $Signature
            );
        }

        return $arr;
    }

    public static function getResulteFromResponse($rs, $type = 1) {

        $arr = array();
        $arr = explode("|", utf8_encode($rs['CardInputResult']));
        // card_type = 1 for gate
        if ($type == 1 || $type == 2 || $type == 4) {
            switch ($arr['0']) {
                case '00': $arr['1'] = 'Giao dịch thành công.';
                    break;
                case '01': $arr['1'] = 'Tham số đầu vào (CardType) không hợp lệ.';
                    break;
                case '02': $arr['1'] = 'Tham số đầu vào (Amount) không hợp lệ.';
                    break;
                case '03': $arr['1'] = 'Tham số đầu vào(Phone) không hợp lệ.';
                    break;
                case '04': $arr['1'] = 'Tham số đầu vào (TelcoID) không hợp lệ.';
                    break;
                case '05': $arr['1'] = 'Tham số đầu vào (CustomerID) không hợp lệ.';
                    break;
                case '06': $arr['1'] = 'Tham số đầu vào (TransactionID) không hợp lệ.';
                    break;
                case '07': $arr['1'] = 'Tham số đầu vào (Serial) không hợp lệ.';
                    break;
                case '08': $arr['1'] = 'Tham số đầu vào (PIN) không hợp lệ.';
                    break;
                case '15': $arr['1'] = 'Thẻ đã được sử dụng.';
                    break;
                case '16': $arr['1'] = 'Thẻ không tồn tại hoặc chưa được kích hoạt.';
                    break;
                case '17': $arr['1'] = 'Thông tin mã thẻ không đúng định dạng.';
                    break;
                case '18': $arr['1'] = 'Tài khoản bị tạm khóa trong 5 phút.';
                    break;
                case '19': $arr['1'] = 'Thẻ đã hết hạn sử dụng.';
                    break;
                case '20': $arr['1'] = 'Chữ ký điện tử không hợp lệ.';
                    break;
                case '81': $arr['1'] = 'Đại lý không hợp pháp.';
                    break;
                case '82': $arr['1'] = 'Đại lý chưa có số tiền hợp lệ.';
                    break;
                case '83': $arr['1'] = 'Số tiền không hợp lệ.';
                    break;
                case '84': $arr['1'] = 'Tổng tiền đại lý không hợp lệ.';
                    break;
                case '85': $arr['1'] = 'Số tiền giao dịch không hợp lệ.';
                    break;
                case '86': $arr['1'] = 'Tài khoản đại lý không đủ tiền thực hiện giao dịch.';
                    break;
                case '87': $arr['1'] = 'Thông tin chiết khấu không hợp lệ.';
                    break;
                case '96': $arr['1'] = 'Dịch vụ không hợp lệ.';
                    break;
                case '97': $arr['1'] = 'Mã đối tác không hợp lệ.';
                    break;
                case '98': $arr['1'] = 'Địa chỉ IP không hợp lệ.';
                    break;
                case '99': $arr['1'] = 'Giao dịch thất bại.';
                    break;
                case '100': $arr['1'] = 'Hệ thống đang bảo trì.';
                    break;
            }
        } elseif ($type == 3) {
            switch ($arr['0']) {
                case '00': $arr['1'] = 'Giao dịch thành công.';
                    break;
                case '01': $arr['1'] = 'Tham số đầu vào (CardType) không hợp lệ.';
                    break;
                case '02': $arr['1'] = 'Tham số đầu vào (Amount) không hợp lệ.';
                    break;
                case '03': $arr['1'] = 'Tham số đầu vào(Phone) không hợp lệ.';
                    break;
                case '04': $arr['1'] = 'Tham số đầu vào (TelcoID) không hợp lệ.';
                    break;
                case '05': $arr['1'] = 'Tham số đầu vào (CustomerID) không hợp lệ.';
                    break;
                case '06': $arr['1'] = 'Tham số đầu vào (TransactionID) không hợp lệ.';
                    break;
                case '07': $arr['1'] = 'Tham số đầu vào (Serial) không hợp lệ.';
                    break;
                case '08': $arr['1'] = 'Tham số đầu vào (PIN) không hợp lệ.';
                    break;
                case '15': $arr['1'] = 'Thẻ đã được sử dụng.';
                    break;
                case '16': $arr['1'] = 'Thẻ không tồn tại hoặc chưa được kích hoạt.';
                    break;
                case '17': $arr['1'] = 'Thông tin mã thẻ không đúng định dạng.';
                    break;
                case '18': $arr['1'] = 'Mã thẻ và số PIN thẻ không khớp.';
                    break;
                //case '19': $arr['1'] =	'Thẻ đã hết hạn sử dụng.'; break;
                case '20': $arr['1'] = 'Chữ ký điện tử không hợp lệ.';
                    break;
                case '81': $arr['1'] = 'Đại lý không hợp pháp.';
                    break;
                case '82': $arr['1'] = 'Đại lý chưa có số tiền hợp lệ.';
                    break;
                case '83': $arr['1'] = 'Số tiền không hợp lệ.';
                    break;
                case '84': $arr['1'] = 'Tổng tiền đại lý không hợp lệ.';
                    break;
                case '85': $arr['1'] = 'Số tiền giao dịch không hợp lệ.';
                    break;
                case '86': $arr['1'] = 'Tài khoản đại lý không đủ tiền thực hiện giao dịch.';
                    break;
                case '87': $arr['1'] = 'Thông tin chiết khấu không hợp lệ.';
                    break;
                case '96': $arr['1'] = 'Dịch vụ không hợp lệ.';
                    break;
                case '97': $arr['1'] = 'Mã đối tác không hợp lệ.';
                    break;
                case '98': $arr['1'] = 'Địa chỉ IP không hợp lệ.';
                    break;
                case '99': $arr['1'] = 'Giao dịch thất bại.';
                    break;
                case '100': $arr['1'] = 'Hệ thống đang bảo trì.';
                    break;
            }
        }

        return $arr;
    }

    public static function redirectToOnepay($traninfo, $card_type = 'atm') {
        $vpcURL = self::createSignature($traninfo, $card_type);
        header("Location: " . $vpcURL);
        exit();
    }

    public static function checkVisaOnePayTransaction($trans_id) {
        $url = 'https://mtf.onepay.vn/vpcpay/Vpcdps.op';
        $arr = [
            'vpc_Version' => 1,
            'vpc_Command' => 'queryDR',
            'vpc_AccessCode' => '6BEB2546',
            'vpc_Merchant' => 'TESTONEPAY',
            'vpc_MerchTxnRef' => $trans_id,
            'vpc_User' => 'op01',
            'vpc_Password' => 'op123456',
        ];

        return self::callVisaService($arr, $url);
    }

    public static function checkAtmOnePayTransaction($trans_id) {
        $url = 'https://mtf.onepay.vn/onecomm-pay/Vpcdps.op';
        $arr = [
            'vpc_Version' => 1,
            'vpc_Command' => 'queryDR',
            'vpc_AccessCode' => 'D67342C2',
            'vpc_Merchant' => 'ONEPAY',
            'vpc_MerchTxnRef' => $trans_id,
            'vpc_User' => 'op01',
            'vpc_Password' => 'op123456',
        ];

        return self::callVisaService($arr, $url);
    }

    public static function callVisaService($data, $url) {

        // create a variable to hold the POST data information and capture it
        $postData = "";
        $ampersand = "";
        foreach ($data as $key => $value) {
            // create the POST data input leaving out any fields that have no value
            if (strlen($value) > 0) {
                $postData .= $ampersand . urlencode($key) . '=' . urlencode($value);
                $ampersand = "&";
            }
        }

        ob_start();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);

        $response = ob_get_contents();

        ob_end_clean();

        $message = "";

        if (strchr($response, "<html>") || strchr($response, "<html>")) {
            $message = $response;
        } else {
            if (curl_error($ch))
                $message = "%s: s" . curl_errno($ch) . "<br/>" . curl_error($ch);
        }

        curl_close($ch);
        $map = array();

        if (strlen($message) == 0) {
            $pairArray = explode("&", $response);
            foreach ($pairArray as $pair) {
                $param = explode("=", $pair);
                $map[urldecode($param[0])] = urldecode($param[1]);
            }
            $message = self::null2unknown($map, "vpc_Message");
        }

        $merchTxnRef = self::null2unknown($map, "vpc_MerchTxnRef");

        $amount = self::null2unknown($map, "vpc_Amount");
        $locale = self::null2unknown($map, "vpc_Locale");
        $batchNo = self::null2unknown($map, "vpc_BatchNo");
        $command = self::null2unknown($map, "vpc_Command");
        $version = self::null2unknown($map, "vpc_Version");
        $cardType = self::null2unknown($map, "vpc_Card");
        $orderInfo = self::null2unknown($map, "vpc_OrderInfo");
        $receiptNo = self::null2unknown($map, "vpc_ReceiptNo");
        $merchantID = self::null2unknown($map, "vpc_Merchant");
        $authorizeID = self::null2unknown($map, "vpc_AuthorizeId");
        $transactionNo = self::null2unknown($map, "vpc_TransactionNo");
        $acqResponseCode = self::null2unknown($map, "vpc_AcqResponseCode");
        $txnResponseCode = self::null2unknown($map, "vpc_TxnResponseCode");

        // QueryDR Data
        $drExists = self::null2unknown($map, "vpc_DRExists");
        $multipleDRs = self::null2unknown($map, "vpc_FoundMultipleDRs");


        // 3-D Secure Data
        $verType = self::null2unknown($map, "vpc_VerType");
        $verStatus = self::null2unknown($map, "vpc_VerStatus");
        $token = self::null2unknown($map, "vpc_VerToken");
        $verSecurLevel = self::null2unknown($map, "vpc_VerSecurityLevel");
        $enrolled = self::null2unknown($map, "vpc_3DSenrolled");
        $xid = self::null2unknown($map, "vpc_3DSXID");
        $acqECI = self::null2unknown($map, "vpc_3DSECI");
        $authStatus = self::null2unknown($map, "vpc_3DSstatus");

        // AMA Transaction Data
        $shopTransNo = self::null2unknown($map, "vpc_ShopTransactionNo");
        $authorisedAmount = self::null2unknown($map, "vpc_AuthorisedAmount");
        $capturedAmount = self::null2unknown($map, "vpc_CapturedAmount");
        $refundedAmount = self::null2unknown($map, "vpc_RefundedAmount");
        $ticketNumber = self::null2unknown($map, "vpc_TicketNo");


        // Define an AMA transaction output for Refund & Capture transactions
        $amaTransaction = true;
        if ($shopTransNo == "No Value Returned") {
            $amaTransaction = false;
        }

        $errorTxt = "";
        // Show the display page as an error page 
        if ($txnResponseCode == "7" || $txnResponseCode == "No Value Returned") {
            $errorTxt = "Error ";
        }

        $transStatus = "";
        if ($txnResponseCode == "0" && $amount > 0) {
            return [
                'vpc_Amount' => $amount,
                'vpc_Message' => $message,
                'vpc_TxnResponseCode' => $txnResponseCode,
                'vpc_MerchTxnRef' => $merchTxnRef,
                'vpc_OrderInfo' => $orderInfo,
            ];
        }
        return FALSE;
    }

    function getResponseDescription($responseCode) {

        switch ($responseCode) {
            case "0" :
                $result = "Transaction Successful";
                break;
            case "?" :
                $result = "Transaction status is unknown";
                break;
            case "1" :
                $result = "Bank system reject";
                break;
            case "2" :
                $result = "Bank Declined Transaction";
                break;
            case "3" :
                $result = "No Reply from Bank";
                break;
            case "4" :
                $result = "Expired Card";
                break;
            case "5" :
                $result = "Insufficient funds";
                break;
            case "6" :
                $result = "Error Communicating with Bank";
                break;
            case "7" :
                $result = "Payment Server System Error";
                break;
            case "8" :
                $result = "Transaction Type Not Supported";
                break;
            case "9" :
                $result = "Bank declined transaction (Do not contact Bank)";
                break;
            case "A" :
                $result = "Transaction Aborted";
                break;
            case "C" :
                $result = "Transaction Cancelled";
                break;
            case "D" :
                $result = "Deferred transaction has been received and is awaiting processing";
                break;
            case "F" :
                $result = "3D Secure Authentication failed";
                break;
            case "I" :
                $result = "Card Security Code verification failed";
                break;
            case "L" :
                $result = "Shopping Transaction Locked (Please try the transaction again later)";
                break;
            case "N" :
                $result = "Cardholder is not enrolled in Authentication scheme";
                break;
            case "P" :
                $result = "Transaction has been received by the Payment Adaptor and is being processed";
                break;
            case "R" :
                $result = "Transaction was not processed - Reached limit of retry attempts allowed";
                break;
            case "S" :
                $result = "Duplicate SessionID (OrderInfo)";
                break;
            case "T" :
                $result = "Address Verification Failed";
                break;
            case "U" :
                $result = "Card Security Code Failed";
                break;
            case "V" :
                $result = "Address Verification and Card Security Code Failed";
                break;
            default :
                $result = "Unable to be determined";
        }
        return $result;
    }

    static function null2unknown($map, $key) {
        if (array_key_exists($key, $map)) {
            if (!is_null($map[$key])) {
                return $map[$key];
            }
        }
        return "No Value Returned";
    }

}

?>