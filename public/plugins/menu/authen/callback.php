<?php

session_start();
require_once 'SlgOAuth/SlgOAuth.php';
require_once 'SlgOAuth/config.php';

$code = $_GET['code'];
$site = 'http://slg.vn/demo/authen';

if (!empty($code)) {
    $slgOAuth = new SlgOAuth(CLIENT_ID, CLIENT_SECRET, REDIRECT_URI);

    $response = $slgOAuth->getAccessToken($code);
    if (is_object($response) && isset($response->access_token) && $response->access_token) {
        // get user info
        $user = $slgOAuth->callApi('GET', 'apiv1/me', $response->access_token);
        if (is_object($user) && isset($user->id)) {
            $_SESSION['user'] = $user;
        }
    }
}

// close modal box
echo '<script type=text/javascript>
                    window.parent.location = "' . $site . '";
                 </script>';
exit();
?>