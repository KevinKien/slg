<?php

define('PARTNER_LIST_KEY', 'list_partner');
define('APP_ID_KEY', 'app_id');
define('APP_ID_LIST_KEY', 'list_appid');
define('APP_ID_UPDATE_KEY', 'appid_update');
define('CP_ID_KEY', 'cp_id');
define('CP_ID_LIST_KEY', 'list_cpid');
define('CP_ID_UPDATE_KEY', 'cpid_update');
define('LOG_DAU_USER', 'Application_LOG_DAU_USER');
define('LOG_NIU', 'Application_LOG_NIU');

// Error validate code
define('USERNAME_REQUITE', '1');
define('USERNAME_MIN', '2');
define('USERNAME_LOGIN_MIN', '80');
define('USERNAME_MAX', '3');
define('USERNAME_UNIQUE', '4');
define('EMAIL_EMAIL', '5');
define('EMAIL_MAX', '6');
define('EMAIL_UNIQUE', '7');
define('EMAIL_REQUITE', '52');
define('PASSWORD_REQUITE', '8');
define('PASSWORD_MIN', '9');
define('PASSWORD_MAX', '10');
define('PASSWORD_CONFIRM', '11');
define('CLIENT_ID_REQUITE', '12');
define('CLIENT_ID_FALSE', '61');
define('CLIENT_SECRET_REQUITE', '13');
define('CLIENT_SECRET_FALSE', '62');
define('ACCESS_TOKEN_TIMEOUT', '41');
define('ACCESS_TOKEN_REQUITE', '42');
define('REFRESH_TOKEN_REQUITE', '63');
define('VALIDATE_MSG', json_encode(array(
    1 => 'The username field is required.',
    2 => 'The username must be at least 6 characters.',
    3 => 'The username may not be greater than 255 characters.',
    4 => 'The username has already been taken',
    5 => 'The email must be a valid email address.',
    6 => 'The email may not be greater than 255 characters.',
    7 => 'The email has already been taken.',
    8 => 'The password field is required.',
    9 => 'password to short',
    10 => 'password to long',
    11 => 'password_confirmation not correct',
    12 => 'The client_id field is required',
    13 => 'The client_secret field is required.',
    14 => 'authen fair',
    51 => 'Email does not exist',
    52 => 'Email field is required.',
    53 => 'Error: Sign',
    54 => 'Error: Blocked in user administration.',
    61 => 'client_id not exactly',
    62 => 'client_secret not exactly',
    41 => 'Error access_token time out',
    42 => 'Error access_token not esxit',
    63 => 'Error refresh_token not esxit',
    70 => 'Field identify can not be change. Please contact administrator',
    71 => 'Field email can not be change. Please contact administrator',
    72 => 'Field phone can not be change. Please contact administrator',
    80 => 'The username login must be at least 4 characters.',
    200 => 'success'
                )
));

// Error validate payment start 400 end 500

define('NOT_ENOUGH_COINS', '401');
define('FAIL_TRANSFER_COINS_TO_GAME', '402');
define('PAY_SYSTEM_ERROR', '403');
define('ORDER_NOT_FOUND', '404');
define('LIST_ITEMS_APPLE_NOT_FOUND', '405');
define('RECEIPT_NOT_FOUND', '406');


//list os_id
define ('OS_ID',  json_encode( array(
     1 =>'IOS',
     2 =>'Android',
     3 =>'WindowsPhone',
     4 =>'Windows'
    ))
        );