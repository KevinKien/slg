<?php
include("Payment.php");
$payment = new Payment();
$payment->setSecureSecret("198BE3F2E8C75A53F38C1C4A5B6DBA27");
//$payment->setVirtualPaymentUrl("http://10.36.70.34:2012/gateway/vpcpay.do");
$payment->setVirtualPaymentUrl("http://payment.smartlink.com.vn/gateway/vpcpay.do");

/* 1. Tao mot mang cac tham so:
$_params= array("Title" => "BAOKIM JSC", "vpc_Version" => "1.1", "vpc_Command" => "pay", "vpc_AccessCode" => "ECAFAB", "vpc_MerchTxnRef" => "BBT526A21FA4996",
 "vpc_Merchant" => "SMLTEST", "vpc_OrderInfo" => "Pay", "vpc_Amount" => "10200000", "vpc_Locale" => "vn" ,
 "vpc_Currency" => "VND", "vpc_ReturnURL" => "http://pay.bk.local/bank/sml/callback");
 //2. Gui cac tham so toi smartlink
$payment->redirect($_params);
 */

//trong vi du nay lay $_POST lam tham so
unset($_POST["SubButL"]);
unset($_POST["Title"]);
$payment->redirect($_POST);
?>