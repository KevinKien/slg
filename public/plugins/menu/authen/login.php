<?php

require_once 'SlgOAuth/SlgOAuth.php';
require_once 'SlgOAuth/config.php';


$slgOAuth = new SlgOAuth(CLIENT_ID, CLIENT_SECRET, REDIRECT_URI);
$url_login = $slgOAuth->getLoginUrl();
header('location: ' . $url_login);
exit();

?>