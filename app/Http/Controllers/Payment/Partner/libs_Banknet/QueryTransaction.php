<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head><title>Virtual Payment Client Request Details</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<style type='text/css'>
    <!--
    h1       { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; font-weight:100}
    h2.co    { font-family:Arial,sans-serif; font-size:24pt; color:#08185A; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
    h3.co    { font-family:Arial,sans-serif; font-size:16pt; color:#000000; margin-top:0.1em; margin-bottom:0.1em; font-weight:100}
    body     { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#08185A ;background-color:#FFFFFF }
    p        { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#FFFFFF }
    p.bl     { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
    a:link   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
    a:visited{ font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
    a:hover  { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0000 }
    a:active { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#FF0000 }
    td       { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A }
    th       { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#08185A; font-weight:bold; background-color:#E1E1E1; padding-top:0.5em; padding-bottom:0.5em}
    input    { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:bold }
    select   { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:bold }
    textarea { font-family:Verdana,Arial,sans-serif; font-size:8pt; color:#08185A; background-color:#E1E1E1; font-weight:normal; scrollbar-arrow-color:#08185A; scrollbar-base-color:#E1E1E1 }
    -->
</style>
</head>
<body>

    <!-- branding table -->
    <table width='100%' border='2' cellpadding='2' bgcolor='#C1C1C1'><tr><td bgcolor='#E1E1E1' width='90%'><h2 class='co'>&nbsp;Virtual Payment Client - Version 1</h2></td><td bgcolor='#C1C1C1' align='center'><h3 class='co'>Smartlink</h3></td></tr></table>
<table width="100%" border="2" cellpadding="2" >
    <tr>
        <td width="90%" align="center"><b><a href="CreateTransaction.php">1. Create Payment</a> | <a href="QueryTransaction.php" >2. Query Transaction</a></b></td>
    </tr>
</table>

<center><h3>PHP Merchant Example - Request Details</h3></center>

<!-- The "Pay Now!" button submits the form, transferring control to the page detailed below -->
<form action="./VpcDoQuery.php" method="post">

    <!-- get user input -->
    <table width="80%" align="center" border="0" cellpadding='0' cellspacing='0'>
	    <tr>
	        <td colspan="3">&nbsp;<hr width="75%">&nbsp;</td>
	    </tr>
	    <tr bgcolor="#C1C1C1">
	        <td colspan="3" height="25"><p><b>&nbsp;Transaction Fields</b></p></td>
	    </tr>
	    <tr>
	        <td>&nbsp;</td>
	        <td align="right"><b><i> VPC Version: </i></b></td>
	        <td><input type="text" name="vpc_Version" value="1.1" size="20" maxlength="8"></td>
	    </tr>
	    <tr bgcolor="#E1E1E1">
	        <td>&nbsp;</td>
	        <td align="right"><b><i>MerchantID: </i></b></td>
	        <td><input type="text" name="vpc_Merchant" value="SMLTEST" size="20" maxlength="16"></td>
	    </tr>
	    <tr>
	        <td>&nbsp;</td>
	        <td align="right"><b><i>Merchant AccessCode: </i></b></td>
	        <td><input type="text" name="vpc_AccessCode" value="ECAFAB" size="20" maxlength="8"></td>
	    </tr>
	    <tr bgcolor="#E1E1E1">
	        <td>&nbsp;</td>
	        <td align="right"><b><i>Command Type: </i></b></td>
	        <td><input type="text" name="vpc_Command" value="queryDR" size="20" maxlength="16"></td>
	    </tr>
	    <tr>
	        <td>&nbsp;</td>
	        <td align="right"><b><i>Merchant Transaction Reference: </i></b></td>
	        <td><input type="text" name="vpc_MerchTxnRef" value="" size="20" maxlength="40"></td>
	    </tr>
	    
	    <tr>    <td colspan="2">&nbsp;</td> 
	            <td><input type="submit" name="SubButL" value="Query!"></td></tr>
	
	    <tr><td colspan="3">&nbsp;<hr width="75%">&nbsp;</td></tr>
    </table><br/>
  </form>
</body>
</html>