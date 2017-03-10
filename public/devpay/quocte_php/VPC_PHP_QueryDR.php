<?php
// *********************
// START OF MAIN PROGRAM
// *********************

// add the start of the vpcURL querystring parameters
$vpcURL = $_POST["virtualPaymentClientURL"];

// This is the title for display
$title = $_POST["Title"];

// Remove the Virtual Payment Client URL from the parameter hash as we 
// do not want to send these fields to the Virtual Payment Client.
unset($_POST["virtualPaymentClientURL"]);
unset($_POST["SubButL"]);
unset($_POST["Title"]);

// create a variable to hold the POST data information and capture it
$postData = "";

$ampersand = "";
foreach ($_POST as $key => $value) {
    // create the POST data input leaving out any fields that have no value
    if (strlen($value) > 0) {
        $postData .= $ampersand . urlencode($key) . '=' . urlencode($value);
        $ampersand = "&";
    }
}

// Get a HTTPS connection to VPC Gateway and do transaction
// turn on output buffering to stop response going to browser
ob_start();

// initialise Client URL object
$ch = curl_init();

// set the URL of the VPC
curl_setopt($ch, CURLOPT_URL, $vpcURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

// (optional) set the proxy IP address and port
//curl_setopt ($ch, CURLOPT_PROXY, "192.168.21.13:80");

// (optional) certificate validation
// trusted certificate file
//curl_setopt($ch, CURLOPT_CAINFO, "c:/temp/ca-bundle.crt");

//turn on/off cert validation
// 0 = don't verify peer, 1 = do verify
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// 0 = don't verify hostname, 1 = check for existence of hostame, 2 = verify
//curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

// connect
curl_exec($ch);

// get response
$response = ob_get_contents();

// turn output buffering off.
ob_end_clean();

// set up message paramter for error outputs
$message = "";

// serach if $response contains html error code
if (strchr($response, "<html>") || strchr($response, "<html>")) {
    ;
    $message = $response;
} else {
    // check for errors from curl
    if (curl_error($ch))
        $message = "%s: s" . curl_errno($ch) . "<br/>" . curl_error($ch);
}

// close client URL
curl_close($ch);

// Extract the available receipt fields from the VPC Response
// If not present then let the value be equal to 'No Value Returned'
$map = array();

// process response if no errors
if (strlen($message) == 0) {
    $pairArray = split("&", $response);
    foreach ($pairArray as $pair) {
        $param = split("=", $pair);
        $map[urldecode($param[0])] = urldecode($param[1]);
    }
    $message = null2unknown($map, "vpc_Message");
}

// Standard Receipt Data
# merchTxnRef not always returned in response if no receipt so get input
//TK//$merchTxnRef     = $vpc_MerchTxnRef;
$merchTxnRef = $_POST["vpc_MerchTxnRef"];


$amount = null2unknown($map, "vpc_Amount");
$locale = null2unknown($map, "vpc_Locale");
$batchNo = null2unknown($map, "vpc_BatchNo");
$command = null2unknown($map, "vpc_Command");
$version = null2unknown($map, "vpc_Version");
$cardType = null2unknown($map, "vpc_Card");
$orderInfo = null2unknown($map, "vpc_OrderInfo");
$receiptNo = null2unknown($map, "vpc_ReceiptNo");
$merchantID = null2unknown($map, "vpc_Merchant");
$authorizeID = null2unknown($map, "vpc_AuthorizeId");
$transactionNo = null2unknown($map, "vpc_TransactionNo");
$acqResponseCode = null2unknown($map, "vpc_AcqResponseCode");
$txnResponseCode = null2unknown($map, "vpc_TxnResponseCode");

// QueryDR Data
$drExists = null2unknown($map, "vpc_DRExists");
$multipleDRs = null2unknown($map, "vpc_FoundMultipleDRs");


// 3-D Secure Data
$verType = null2unknown($map, "vpc_VerType");
$verStatus = null2unknown($map, "vpc_VerStatus");
$token = null2unknown($map, "vpc_VerToken");
$verSecurLevel = null2unknown($map, "vpc_VerSecurityLevel");
$enrolled = null2unknown($map, "vpc_3DSenrolled");
$xid = null2unknown($map, "vpc_3DSXID");
$acqECI = null2unknown($map, "vpc_3DSECI");
$authStatus = null2unknown($map, "vpc_3DSstatus");

// AMA Transaction Data
$shopTransNo = null2unknown($map, "vpc_ShopTransactionNo");
$authorisedAmount = null2unknown($map, "vpc_AuthorisedAmount");
$capturedAmount = null2unknown($map, "vpc_CapturedAmount");
$refundedAmount = null2unknown($map, "vpc_RefundedAmount");
$ticketNumber = null2unknown($map, "vpc_TicketNo");


// Define an AMA transaction output for Refund & Capture transactions
$amaTransaction = true;
if ($shopTransNo == "No Value Returned") {
    $amaTransaction = false;
}

/*********************
 * END OF MAIN PROGRAM
 *********************/


// FINISH TRANSACTION - Process the VPC Response Data
// =====================================================
// For the purposes of demonstration, we simply display the Result fields on a
// web page.

// Show 'Error' in title if an error condition
$errorTxt = "";
// Show the display page as an error page 
if ($txnResponseCode == "7" || $txnResponseCode == "No Value Returned") {
    $errorTxt = "Error ";
}


$transStatus = "";
if ($txnResponseCode == "0") {
    $transStatus = "Giao dịch thành công";
} elseif ($txnResponseCode != "0") {
    $transStatus = "Giao dịch thất bại";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title><?php echo $title;?> - <?php echo $errorTxt;?>Response Page</title>
    <meta http-equiv="Content-Type" content="text/html, charset=utf8">
    <style type="text/css">
        <!--
        h1 {
            font-family: Arial, sans-serif;
            font-size: 24pt;
            color: #08185A;
            font-weight: 100
        }

        h2.co {
            font-family: Arial, sans-serif;
            font-size: 24pt;
            color: #08185A;
            margin-top: 0.1em;
            margin-bottom: 0.1em;
            font-weight: 100
        }

        h3.co {
            font-family: Arial, sans-serif;
            font-size: 16pt;
            color: #000000;
            margin-top: 0.1em;
            margin-bottom: 0.1em;
            font-weight: 100
        }

        body {
            font-family: Verdana, Arial, sans-serif;
            font-size: 10pt;
            color: #08185A background-color: #FFFFFF
        }

        a:link {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A
        }

        a:visited {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A
        }

        a:hover {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #FF0000
        }

        a:active {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #FF0000
        }

        tr.title {
            height: 25px;
            background-color: #0074C4
        }

        td {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A
        }

        th {
            font-family: Verdana, Arial, sans-serif;
            font-size: 10pt;
            color: #08185A;
            font-weight: bold;
            background-color: #CED7EF;
            padding-top: 0.5em;
            padding-bottom: 0.5em
        }

        input {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A;
            background-color: #CED7EF;
            font-weight: bold
        }

        #background-image {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
            font-size: 12px;
            width: 80%;
            text-align: left;
            border-collapse: collapse;
            background: url("...") 330px 59px no-repeat;
            margin: 20px;
        }

        #background-image th {
            font-weight: normal;
            font-size: 14px;
            color: #339;
            padding: 12px;
        }

        #background-image td {
            color: #669;
            border-top: 1px solid #fff;
            padding: 9px 12px;
        }

        #background-image tfoot td {
            font-size: 11px;
        }

        #background-image tbody td {
            background: url("./back.png");
        }

        * html
#background-image tbody td {
            filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src = 'table-images/back.png', sizingMethod = 'crop');
            background: none;
        }

        #background-image tbody tr:hover td {
            color: #339;
            background: none;
        }

        -->
    </style>
</head>
<body>
<table width='100%' border='2' cellpadding='2' bgcolor='#0074C4'>
    <tr>
        <td bgcolor='#CED7EF' width='90%'>
            <h2 class='co'>&nbsp;Payment Client Example</h2>
        </td>
        <td bgcolor='#0074C4' align='center'>
            <h3 class='co'>OnePAY</h3>
        </td>
    </tr>
</table>
<center>
    <h1><?php echo $transStatus;?></h1>
</center>
<center>
    <table id="background-image" summary="Meeting Results">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Value</th>
            <th scope="col">Description</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td align="center" colspan="4"></td>
        </tr>
        </tfoot>
        <tbody>
        <tr>
            <td><strong><i>Merchant ID </i></strong></td>
            <td><?php
            echo $merchantID;
                ?></td>
            <td>Được cấp bởi OnePAY</td>
        </tr>
        <tr>
            <td><strong><i>Merchant Transaction Reference</i></strong></td>
            <td><?php
            echo $merchTxnRef;
                ?></td>
            <td>ID của giao dịch gửi từ website merchant</td>
        </tr>
        <tr>
            <td><strong><i>Transaction OrderInfo</i></strong></td>
            <td><?php
            echo $orderInfo;
                ?></td>
            <td>Tên hóa đơn</td>
        </tr>
        <tr>
            <td><strong><i>Purchase Amount</i></strong></td>
            <td><?php
            echo $amount;
                ?></td>
            <td>Số tiền được thanh toán</td>
        </tr>

        <tr>
            <td><strong><i>VPC Transaction Response Code </i></strong></td>
            <td><?php
            echo $txnResponseCode;
                ?></td>
            <td>Mã trạng thái giao dịch trả về</td>
        </tr>
        <tr>
            <td><strong><i>Transaction Response Code Description </i></strong></td>
            <td><?php echo getResponseDescription($txnResponseCode);
                ?></td>
            <td>Trạng thái giao dịch</td>
        </tr>
        <tr>
            <td><strong><i>Message</i></strong></td>
            <td><?php
            echo $message;
                ?></td>
            <td>Thông báo từ cổng thanh toán</td>
        </tr>
<?php
// only display the following fields if not an error condition
        if ($txnResponseCode != "7" && $txnResponseCode != "No Value Returned") {
            ?>
        <tr>
            <td><strong><i>Transaction Number</i></strong></td>
            <td><?php
    echo $transactionNo;
                ?></td>
            <td>ID giao dịch trên cổng thanh toán</td>
        </tr>

                <?php

        }
        ?>
        </tbody>
    </table>
</center>
</body>
</html>

<?
// End Processing

//  ----------------------------------------------------------------------------

// This method uses the QSI Response code retrieved from the Digital
// Receipt and returns an appropriate description for the QSI Response Code
//
// @param $responseCode String containing the QSI Response Code
//
// @return String containing the appropriate description
//
function getResponseDescription($responseCode)
{

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
        default  :
            $result = "Unable to be determined";
    }
    return $result;
}

//  -----------------------------------------------------------------------------

// If input is null, returns string "No Value Returned", else returns value corresponding to given key
function null2unknown($map, $key)
{
    if (array_key_exists($key, $map)) {
        if (!is_null($map[$key])) {
            return $map[$key];
        }
    }
    return "No Value Returned";
}

//  ----------------------------------------------------------------------------
