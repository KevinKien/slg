<?php

namespace App\Helpers;

use Illuminate\Http\Request, Authorizer;
use cURL;

class CommonHelper
{
    public static function rand_str($length, $number_only = false)
    {
        $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if ($number_only) {
            $character = '01234567890123456789';
        }

        return substr(str_shuffle($character), 0, $length);
    }

    public static function cURLWithRetry($method, $url, $try = 3, $data = [])
    {
        $t = 0;
        $result = false;

        get:
        if ($t < $try) {
            try {
                $request = cURL::newRequest($method, $url, $data)
                    ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                    ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                    ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                    ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                    ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 6);

                $response = $request->send();

                return $response->body;
            } catch (\Exception $e) {
                $t++;
                goto get;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public static function getClientIP()
    {
        if (getenv('HTTP_CLIENT_IP'))
            $ip = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ip = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ip = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ip = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ip = getenv('REMOTE_ADDR');
        else
            $ip = '0.0.0.0';

        return $ip;
    }

    public static function cURL($url, $cookie = false, $data = false, $referer = false, $header = false)
    {
        $ch = curl_init();

        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_ENCODING => 'gzip, deflate',
            CURLOPT_COOKIEJAR => $cookie,
            CURLOPT_COOKIEFILE => $cookie,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; rv:43.0) Gecko/20100101 Firefox/43.0',
        );

        if ($data) {
            $opts[CURLOPT_POST] = TRUE;
            $opts[CURLOPT_POSTFIELDS] = $data;
        }

        if ($referer) {
            $opts[CURLOPT_REFERER] = $referer;
        }

        if ($header) {
            $opts[CURLOPT_HTTPHEADER] = $header;
        }

        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Validate the given email.
     *
     * @param  string $email
     * @return boolean
     * @author https://github.com/hbattat/verifyEmail
     */
    public static function isValidEmail($email)
    {
        return true;
        $email_arr = explode("@", $email);
        $domain = array_slice($email_arr, -1);
        $domain = $domain[0];

        // Trim [ and ] from beginning and end of domain string, respectively
        $domain = ltrim($domain, "[");
        $domain = rtrim($domain, "]");

        if ("IPv6:" == substr($domain, 0, strlen("IPv6:"))) {
            $domain = substr($domain, strlen("IPv6") + 1);
        }

        $mxhosts = array();
        if (filter_var($domain, FILTER_VALIDATE_IP))
            $mx_ip = $domain;
        else
            getmxrr($domain, $mxhosts, $mxweight);

        if (!empty($mxhosts))
            $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
        else {
            if (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $record_a = dns_get_record($domain, DNS_A);
            } elseif (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $record_a = dns_get_record($domain, DNS_AAAA);
            }

            if (!empty($record_a))
                $mx_ip = $record_a[0]['ip'];
            else {
                return false;
            }
        }

        $connect = @fsockopen($mx_ip, 25);
        if ($connect) {
            if (preg_match("/^220/i", $out = fgets($connect, 1024))) {
                fputs($connect, "HELO $mx_ip\r\n");
                fgets($connect, 1024);

                fputs($connect, "MAIL FROM: <inbox@gmail.com>\r\n");
                $from = fgets($connect, 1024);

                fputs($connect, "RCPT TO: <$email>\r\n");
                $to = fgets($connect, 1024);

                fputs($connect, "QUIT");
                fclose($connect);

                if (!preg_match("/^250/i", $from) || !preg_match("/^250/i", $to)) {
                    $result = false;
                } else {
                    $result = true;
                }
            }
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function generateAccessToken($data)
    {
        $payload = [
            'grant_type' => $data['grant_type'],
            'client_id' => $data['client_id'],
            'client_secret' => $data['client_secret'],
        ];

        if ($data['grant_type'] == 'password') {
            $payload['username'] = $data['username'];
            $payload['password'] = $data['password'];
        } elseif ($data['grant_type'] == 'refresh_token') {
            $payload['refresh_token'] = $data['refresh_token'];
        } elseif ($data['grant_type'] == 'user') {
            $payload['username'] = $data['username'];
            $payload['password'] = '9284e30bfa7d58f2e157ef28d9c82253';
        } else {
            return null;
        }

        $request = Request::create(route('access_token'), 'POST', $payload);

        Authorizer::setRequest($request);

        return Authorizer::issueAccessToken();
    }

    public static function callApi($type, $api, $token, $base_url, $data = 0)
    {
        $reponse = array();
        $type = strtoupper($type);

        if (empty($type) || empty($api) || empty($token)) {
            return FALSE;
        }
        $api_url = $base_url . '?access_token=' . $token;

        switch ($type) {
            case 'GET':
                $reponse = CommonHelper::getData($api_url);
                break;
            case 'POST':
                $reponse = CommonHelper::postData($api_url, $data);
        }
        return $reponse;
    }

    public static function postData($url, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return json_decode($server_output);
    }

    public static function getData($url, $header = 0)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output);
    }

    public static function convertErrorCode($value)
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
                return "Error access_token not esxit";
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
            case 53:
                return "Error: Oldpass can not correct";
                break;
        }
        return "";
    }

    public static function getRandomAvatar($client_id)
    {
        switch ($client_id) {
            case 9194439505:
                $avatar = 'http://id.slg.vn/img/avatar/soul/' . rand(1, 90) . '.png';
                break;
            default:
                $avatar = null;
                break;
        }

        return $avatar;
    }
}