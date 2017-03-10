<?php
session_start();
$user = '';
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
//print_r($_SESSION);
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <!-- Start of Header control -->
        <title>SLG | Free to Play MMORPG Portal</title>
        <link href="/demo/authen/common.css" rel="stylesheet">
        <script src="/demo/authen/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script src="/demo/authen/mc.js?v=4" type="text/javascript"></script>
    </head> 
    <body>
        <?php if (!is_object($user)): ?>
            <div class="user_join_before">	
                <a onclick="slg_check_loginming();" rel="" href="javascript:void(0);" class="login">ĐĂNG NHẬP</a>				
                <a onclick="slg_register();" href="javascript:void(0);" class="register">ĐĂNG KÝ</a>
            </div>
        <?php else: ?>
            <h4>hi, <?php echo $user->name; ?></h4>
            <a class="register" href="javascript:void(0);" onclick="slg_logout();">Thoát</a>
        <?php endif; ?>
    </body>
</html>