<?php

session_start();
require_once 'SlgOAuth/SlgOAuth.php';
require_once 'SlgOAuth/config.php';

$logout = $_GET['logout'];
$user = $_SESSION['user'];

if (is_object($user)) {
    unset($_SESSION['user']);
}
if (empty($logout)) {
    $callback = urlencode('http://slg.vn/slg_logout');
    $url = 'http://id.slg.vn/auth/logout?callback=' . $callback;
    header('location:' . $url);
} else {
    echo '<script type=text/javascript>
            window.parent.location = window.parent.location;
         </script>';
}
exit();
?>