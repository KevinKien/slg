<?php
function convertErrorCode($value)
{
    switch ($value) {
        case 200:
            return "success";
            break;
        case 1:
            return "Error: username or password not exist";
            break;
        case 2:
            return "Error: input paramater";
            break;
        case 3:
            return "Error: Sign ";
            break;
        case 4:
            return "Error: Blocked in user administration.";
            break;
        case 10:
            return "Error: Username not exist";
            break;
        case 11:
            return "Error: Token not exist";
            break;
        case 13:
            return "Error: username exist";
            break;
        case 14:
            return "Error: The username cannot begin with a space";
            break;
        case 15:
            return "Error: The username cannot end with a space";
            break;
        case 16:
            return "Error: The username cannot contain multiple spaces in a row";
            break;
        case 17:
            return "Error: The username contains an illegal character";
            break;
        case 18:
            return "Error: The username is too long";
            break;
        case 19:
            return "Error: other";
            break;
        case 20:
            return "Error: Account unactive";
            break;
        case 21:
            return "Error: not enough money";
            break;
        case 22:
            return "Error: card error";
            break;
        case 23:
            return "Error: SignIn return token is too 3 times";
            break;
        case 24:
            return "Error: requestid exist";
            break;
        case 25:
            return "Error: can't get info with account's admin";
            break;
        case 26:
            return "Error card: card used";
            break;
        case 27:
            return "Error card: card not exist";
            break;
        case 28:
            return "Error card: Card not activated";
            break;
        case 29:
            return "Error card: Card expired";
            break;
        case 30:
            return "Error card: Other";
            break;
        case 31:
            return "empty data";
            break;
        case 32:
            return "Error command";
            break;
        case 41:
            return "Error access_token time out";
            break;
        case 42:
            return "Error access_token does not exist";
            break;
        case 43:
            return "The password is too short";
            break;
        case 44:
            return "The password is too long";
            break;
        case 45:
            return "appid is not exsit";
            break;
        case 46:
            return "call api to game is fail";
            break;
        case 47:
            return "error exception";
            break;
        case 48:
            return "error json decode data";
            break;
        case 49:
            return "Error: email exist";
            break;
    }
    return "";
}

function call_api($uri, $payload)
{
    $ch = curl_init();

    $url = 'http://api.slg.vn/tl/v1/' . $uri . '?' . http_build_query($payload);

    $opts = [
        CURLOPT_URL => $url,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_ENCODING => 'gzip, deflate',
        CURLOPT_USERAGENT => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (SLG 1.0; rv:1.0) Gecko/20100101 SLG/1.0',
    ];

    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//Kiểm tra đầu vào

if (!isset($_GET['appid']) || $_GET['appid'] != 17541234 || !isset($_GET['requestid']) || !isset($_GET['timereq']) || !isset($_GET['command']) || !isset($_GET['data'])) {
    $code = 2;
    $data = null;

    goto result;
}

//thông số mặc định
$code = 31;
$data = null;
$app_id = 1008;
$client_id = 2044387389;
$client_secret = 'mqn5EndQAnoZEYfop5CN';
$cpid = (isset($_GET['cpid']) && $_GET['cpid'] !== '') ? $_GET['cpid'] : 300000000;
$sub_cpid = (isset($_GET['sub_cpid']) && $_GET['sub_cpid'] !== '') ? $_GET['sub_cpid'] : 0;
//

if (isset($_GET['data']))
{
    $input = json_decode(urldecode($_GET['data']));
}

switch ($_GET['command']) {
    case 'LOGIN':
        $payload = [
            'username' => $input->username ?: '',
            'password' => $input->password ?: '',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'os_id' => $input->os_id ?: '',
            'cpid' => $cpid,
        ];

        $request = call_api('user/login', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
            $data = $response['data'];
        } else {
            $code = 19;
            $message = $response['message'];
        }

        break;
    case 'SAVE_DEVICE_TOKEN';
        $payload = [
            'uid' => $input->uid ?: '',
            'device_id' => $input->device_token ?: '',
            'client_id' => $client_id,
            'os_id' => $input->os_id ?: '',
        ];

        $request = call_api('device/store', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
        } else {
            $code = 2;
        }
        break;
    case 'GET_USER_INFO';
        $payload = [
            'access_token' => $input->access_token ?: '',
            'client_id' => $client_id,
        ];

        $request = call_api('user/information', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        $code = $response['errorCode'];
        $data = $response['data'];

        break;
    case 'REGISTER';
        $payload = [
            'username' => $input->username ?: '',
            'password' => $input->password ?: '',
            'password_confirmation' => $input->password ?: '',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'email' => $input->email ?: $input->username . '_' . rand(10000, 99999) . '@slg.vn',
            'os_id' => $input->os_id ?: '',
            'cpid' => $cpid,
        ];

        $request = call_api('user/register', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
            $data = $response['data'];
        } else {
            $code = 19;
            $message = $response['message'];
        }

        break;
    case 'CHANGE_PASS';
        $payload = [
            'access_token' => $input->access_token ?: '',
            'password' => $input->pass_new ?: '',
            'client_id' => $client_id
        ];

        $request = call_api('user/change-password', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        $code = $response['errorCode'];

        break;
    case 'CHANGE_USER_INFO';
        $payload = [
            'access_token' => $input->access_token ?: '',
            'client_id' => $client_id,
            'email' => $input->email ?: '',
            'fullname' => $input->full_name ?: '',
            'identify' => $input->card_no ?: '',
            'address' => $input->address ?: '',
        ];

        $request = call_api('user/update-information', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        $code = $response['errorCode'];

        break;
    case 'LOGIN_FACEBOOK':
        $payload = [
            'access_token' => $input->access_token_facebook ?: '',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'os_id' => $input->os_id ?: '',
            'cpid' => $cpid,
        ];

        $request = call_api('user/login-facebook', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
            $data = $response['data'];
        } else {
            $code = 19;
            $message = $response['message'];
        }

        break;
    case 'UPDATE_INFO_FACEBOOK'; //API tạo tài khoản cho người chơi FB => không khả thi vì trên SLQ, login FB là đã đăng ký luôn rồi.
//    chưa code hết
//
//        $payload = [
//            'access_token' => $input->access_token ?: '',
//            'client_id' => $client_id,
//            'email' => $input->email ?: '',
//            'username' => $input->username ?: '',
//            'password' => $input->password ?: '',
//        ];
//
//        $request = call_api('user/update-user-facebook', $payload);
//
//        $response = json_decode($request, true);
//
//        if (empty($request) || empty($response)) {
//            break;
//        }
//
        $code = 200;

        break;
    case 'CHECK_LOGIN';
        $payload = [
            'access_token' => $input->access_token ?: '',
            'client_id' => $client_id,
        ];

        $request = call_api('user/validate-access-token', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
        } else {
            $code = 42;
            $message = $response['message'];
        }

        break;
    case 'GET_ALL_LOG_DEVICE';
        $payload = [
            'client_id' => $client_id,
        ];

        $request = call_api('device/log', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        $code = $response['errorCode'];
        $data = $response['data'];

        break;
    case 'GET_LOG_DEVICE_BY_USER';
        $payload = [
            'client_id' => $client_id,
            'uid' => $input->uid,
        ];

        $request = call_api('device/log', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        $code = $response['errorCode'];
        $data = $response['data'];

        break;
    case 'GET_LOG_TRANSACTION'; //vì bảng log charge SLG không có app_id nên không union select như FPAY
        $payload = [
            'uid' => $input->uid,
            'limit' => $input->limit,
            'app_id' => $app_id,
        ];

        $request = call_api('user/transaction-log', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        $code = $response['errorCode'];
        $data = $response['data'];

        break;
    case 'PURCHASE_TELCO';

        $code = 200;
        break;
    case 'PAYMENT_IN_GAME';

        $code = 200;
        break;
    case 'PAYMENT_TELCO';

        $code = 200;
        break;
    case 'PURCHASE_IN_APP';

        $code = 200;
        break;
    case 'GET_LIST_NEWS_BY_TYPE';

        $code = 200;
        break;
    case 'GET_LIST_NEWS';

        $code = 200;
        break;
    case 'GET_LIST_NEWS_HD';

        $code = 200;
        break;
    case 'HOME_PAGE';
        $data = "http://tl.slg.vn/";
        $code = 200;
        break;
    case 'CHECK_APPROVAL';
        $code = 200;
        break;
    case 'GET_LIST_PRODUCT';
        $code = 200;
        break;
    case 'GET_LIST_PRODUCT_APPLE';
        $code = 200;
        break;
    case 'CHECK_ACCESS_TOKEN';
        $payload = [
            'access_token' => $input->access_token ?: '',
            'client_id' => $client_id,
        ];

        $request = call_api('user/validate-access-token', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
        } else {
            $code = 42;
            $message = $response['message'];
        }

        break;
    case 'LOGIN_BY_DEVICE';
        $payload = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'os_id' => $input->os_id,
            'device_id' => $input->device_token,
            'cpid' => $cpid,
        ];

        $request = call_api('user/login-device', $payload);

        $response = json_decode($request, true);

        if (empty($request) || empty($response)) {
            break;
        }

        if ($response['error_code'] == 200) {
            $code = 200;
            $data = $response['data'];
        } else {
            $code = 19;
            $message = $response['message'];
        }

        break;
}

result:

header('Content-type: application/json; charset=utf-8');
echo json_encode([
    'errorCode' => $code,
    'errorMessage' => isset($message) ? $message : convertErrorCode($code),
    'data' => $data,
]);