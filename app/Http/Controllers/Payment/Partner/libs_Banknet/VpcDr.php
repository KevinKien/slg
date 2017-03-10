<?php
	include("Payment.php");
	$payment = new Payment();
	$payment->setSecureSecret("198BE3F2E8C75A53F38C1C4A5B6DBA27");
	
	$isCheckSumOK = $payment->checkSum($_GET);
	$txnResponseCode = $payment->getParameter("vpc_ResponseCode");
//	$txnResponseCode = $payment->getParameter("vpc_TxnResponseCode");
	
	$result = "";
	$message = "";
	if ($txnResponseCode != null) {
		if ($txnResponseCode == "0") {
			if($isCheckSumOK) {
				$result = "<FONT color='blue'><strong>SUCCESS</strong></FONT>";
				$message = "Giao dich thanh cong";
			} else {
				$result = "<FONT color='red'><strong>ERROR</strong></FONT>";
				$message = "Sai ma xac thuc";
			}
		} else {
			$result = "<FONT color='red'><strong>ERROR</strong></FONT>";
			$message = $payment->getResponseDescription($txnResponseCode);
		}
	} else {
		$result = "<FONT color='red'><strong>ERROR</strong></FONT>";
		$message = "Khong co gia tri tra ve";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Response Page</title>
        <meta http-equiv="Content-Type" content="text/html, charset=utf-8">
    </head>
    <body>
		<!-- start branding table -->
		<table width='100%' border='2' cellpadding='2' bgcolor='#C1C1C1'>
			<tr>
				<td bgcolor='#E1E1E1' width='90%'><h2 class='co'>&nbsp;Virtual Payment Client - Version 2.0</h2></td>
				<td bgcolor='#C1C1C1' align='center'><h3 class='co'>Smartlink</h3></td>
			</tr>
		</table>
		<table width="100%" border="2" cellpadding="2" >
		    <tr>
		        <td width="90%" align="center"><b><a href="CreateTransaction.php">1. Create Payment</a> | <a href="QueryTransaction.php" >2. Query Transaction</a></b></td>
		    </tr>
		</table>
		<!-- end branding table -->
        <!-- End Branding Table -->
        <h3>Result: <?=$result?></h3>
        <h3><?=$message?></h3>
    </body>
</html>