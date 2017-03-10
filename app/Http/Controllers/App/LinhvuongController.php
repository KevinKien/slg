<?php

namespace App\Http\Controllers\App;

use \App, Cache;
use App\Helpers\UserHelper;
use \SESSION;
use Illuminate\Support\Facades\Redis;
use Validator, cURL;
use App\Models\User;
use App\Models\UserGame;
use App\Models\MerchantApp;
use App\Models\LogBuyItemSoha;
use App\Helpers\OpenUser;
use App\Helpers\MailHelper;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\Games\GameServices;
use App\Models\Merchant_app_cp;
use App\Helpers\Logs\RevenueLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;

class LinhvuongController extends Controller {

    public static $game_id = 1001;
    public $client_id = '6643224839';
    public $client_secret = 'YCM8Ym23Ko3iTBUR2u28';
    public $redirect_uri = 'https://app.slg.vn/linhvuong/FBcallback';

    const AUTHORIZE_URL = 'http://id.slg.vn/oauth/authorize';
    const REGISTER_URL = 'http://id.slg.vn/oauth/register';
    const ACCESS_TOKEN_URL = 'http://id.slg.vn/oauth/access_token';
    const API_URL = 'http://api.slg.vn';

    public function __construct() {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    private $linhvuong_fbconfig = array(
        'app_id' => '509286652582871',
        'app_secret' => '1044f41793f957d5a1094b7cdb6d252f',
        'callback' => 'https://apps.facebook.com/linhvuongtruyenkyslg',
        'default_graph_version' => 'v2.5',
        'enable_beta_mode' => true,
        'http_client_handler' => 'guzzle');

    public function getLinhvuongfblogin(LaravelFacebookSdk $fb) {
        $scope = array('email', 'read_stream', 'user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me');
        $loginUrl = $login_url = $fb->getLoginUrl($scope);
        echo ' <script type="text/javascript">
                window.parent.location.href="' . $loginUrl . '";
             </script>';
        exit;
    }

    private function createAppFBLinhvuong() {
        $config = $this->linhvuong_fbconfig;

        App::bind('fblinhvuong', function ($app) use($config) {
            if (!isset($config['persistent_data_handler'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }

            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });

        return App::make('fblinhvuong');
    }

    public function fblinhvuong(Request $request) {
        dd(test);
        $config = $this->linhvuong_fbconfig;
        $fb = $this->createAppFBLinhvuong();

        try {
            $token = $fb->getCanvasHelper()->getAccessToken();
        } catch (FacebookSDKException $e) {
            // Failed to obtain access token
            dd($e->getMessage());
        }

        // $token will be null if the user hasn't authenticated your app yet
        if (!$token) {
            $scope = array('email', 'user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me');
            $callback = $config['callback'];
            $loginUrl = $fb->getLoginUrl($scope, $callback);
            echo ' <script type="text/javascript">
                    window.parent.location.href="' . $loginUrl . '";
                 </script>';
            exit;
        } else {
//            if (!$token->isLongLived()) {
//                // OAuth 2.0 client handler
//                $oauth_client = $fb->getOAuth2Client();
//
//                // Extend the access token.
//                try {
//                    $token = $oauth_client->getLongLivedAccessToken($token);
//                } catch (FacebookSDKException $e) {
//                    dd($e->getMessage());
//                }
//            }

//            $fb->setDefaultAccessToken($token);
//
//            // Get basic info on the user from Facebook.
//            try {
//                $response = $fb->get('/me?fields=id,name,email');
//            } catch (FacebookSDKException $e) {
//                dd($e->getMessage());
//            }
//
//            // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
//            $facebook_user = $response->getGraphUser();
            // dd($facebook_user);

            $t = 0;
            $facebook_user = [];

            get:
            if ($t < 3) {
                try {
                    $_request = cURL::newRequest('get', 'https://graph.facebook.com/me?fields=name,email&access_token=' . $token->getValue())
                        ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                        ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                        ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                        ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                        ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

                    $response = $_request->send();

                    $facebook_user = json_decode($response->body, true);
                } catch (\Exception $e) {
                    $t++;
                    goto get;
                }
            }

            if (!isset($facebook_user['id'])) {
                dd('Unexpected error.');
            }

            $pass = str_random(8);

            $name = 'fb' . $facebook_user['id'];

            if (!isset($facebook_user['email']) || empty($facebook_user['email'])) {
                $user = User::findOpenUserByName($name);
            } else {
                $user = User::findOpenUserByEmail($facebook_user['email']);
            }

            if (!$user) {
                $newuser = array(
                    'name' => $name,
                    'email' => isset($facebook_user['email']) ? $facebook_user['email'] : ($facebook_user['id'] . '@facebook.com'),
                    'password' => bcrypt($pass),
                    'provider' => 'facebook',
                    'provider_id' => $facebook_user['id'],
                    'fullname' => $facebook_user['name'],
                    'active' => 1,
                );

                $user = User::firstOrCreate($newuser);

                if (isset($facebook_user['email']) && !empty($facebook_user['email'])) {
                    MailHelper::sendMailWelcome($user, $pass);
                }
            }

            if (Auth::loginUsingId($user->id)) {
//                $slug = 'linh-vuong';
//
//                $apps = Cache::get('active_app_list');
//                if (isset($apps[$slug])) {
//                    $game_id = $apps[$slug];
//                } else {
//                    $game = MerchantApp::where('slug', $slug)->first();
//                    $game_id = $game->id;
//                }
//
                //lưu bảng danh sách game của người chơi
//                $user_game = UserGame::firstOrNew(['uid' => $user->id, 'app_id' => static::$game_id]);
//                $user_game->updated_at = date('Y-m-d H:i:s');
//                $user_game->save();

                $servers = GameServices::getServerList(static::$game_id);
                $server_user = UtilHelper::gamePlaynow(static::$game_id, $user->id);

                UserHelper::logUserGame($user->id, static::$app_id, static::$game_id, 100000011);

                return view('fbapp.linhvuong.serverlist', ['user' => $user, 'servers' => $servers, 'server_user' => $server_user]);
            } else {
                dd('Authen error');
            }
        }
        return;
    }

    public function getCheckuser(Request $request) {
        if (Auth::check()) {
            echo ' <script type="text/javascript">
                    window.parent.location.href="https://app.slg.vn/linhvuong/login_slg";
                 </script>';
            exit;
        }
        dd('error');
        return;
    }

    public function login_slg() {
        //$slgOAuth = new SlgOAuth($client_id, $client_secret, $ridirect_uri);
        $url_login = $this->getLoginUrl();
        print_r($url_login);
        die;
        header('location: ' . $url_login);
        exit();
    }

    public function getFBcallback() {
        $code = $_GET['code'];
        $site = 'http://slg.vn/demo/authen';

        if (!empty($code)) {
            $slgOAuth = new SlgOAuth($this->client_id, $this->client_secret, $this->redirect_uri);

            $response = $slgOAuth->getAccessToken($code);
            if (isset($response->error) && $response->error == 'invalid_request') {
                header('location: ' . '//apps.facebook.com/linhvuong/login_slg');
            }
            print_r($response);
            $_SESSION['access_token'] = $response->access_token;
            if (is_object($response) && isset($response->access_token) && $response->access_token) {
                // get user info
                $user = $slgOAuth->callApi('GET', 'apiv1/me', $response->access_token);
                if (is_object($user) && isset($user->id)) {
                    $_SESSION['user'] = $user;
                }
            }
        }
        echo "<pre>";
        print_r($_SESSION['userinfo']);
        echo "</pre>";
        print_r($_SESSION['user']);
        die;
    }

    public function getLoginUrl() {
        $response_type = 'code';
        if (empty($this->client_id) || empty($this->client_secret) || empty($this->redirect_uri)) {
            return FALSE;
        }
        $url = self::AUTHORIZE_URL . '?client_id=' . $this->client_id .
                '&redirect_uri=' . $this->redirect_uri .
                '&response_type=' . $response_type;
        return $url;
    }

    public function getAccessToken($code) {
        if (empty($code)) {
            return FALSE;
        }
        try {
            $data = array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri' => $this->redirect_uri,
                'code' => $code,
            );

            return $this->postData(self::ACCESS_TOKEN_URL, $data);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function get_index(Request $request) {
//        $app_id = '18903324';
        //$slg_user = OpenUser::firstOrCreate('soha', $usersh);
        //setlogin 
//        $user = Auth::user();
        //lastest played server
//        $server_lastest_user = UtilHelper::gamePlaynow($app_id, $user->id);
//        dd($user->id . '_' . $server_lastest_user);
    }

    public function getSlg(Request $request) {
        if (!Auth::check()) {
            dd('login error');
            return;
        }

        $user = Auth::user();
        $server_id = Input::get('server');

        $service = GameServices::createService(static::$game_id);
        $get_game_url = $service->getLoginUrl($user, $server_id);
        $servers = GameServices::getServerList(static::$game_id);

        UtilHelper::gamePlaynow(static::$game_id, $user->id, $server_id);
        UserHelper::logUserGame($user->id, static::$game_id, 0, intval($server_id));

        return view('game.linhvuong.gameserver', ['url' => $get_game_url, 'servers' => $servers, 'server_id' => $server_id]);
    }

    public function getSoha(Request $request) {
        $signed_request = Input::get('signed_request');
        $server_lastest_user = 0;
        $slg_user = NULL;
        $result_api = array();
        if (!empty($signed_request)) {
            //echo '$signed_request: '.$signed_request.'<br/>';

            /*
              9db070d1d9726d467f00d98e95a35e90
              secret: f98916230e62cdc01e54578a2aeae01f
             *              */
            $result = $this->parse_signed_request($signed_request, 'f98916230e62cdc01e54578a2aeae01f');
            if (!$result) {
                dd('Invalid Signature.');
                exit;
            }

            $t = 0;
            get:
            if ($t < 3) {
                try {
                    $_request = cURL::newRequest('get', 'http://soap.soha.vn/api/a/GET/me/info?access_token=' . $result['access_token'])
                        ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                        ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                        ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                        ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                        ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

                    $response = $_request->send();

                    $result_api = json_decode($response->body);
                } catch (\Exception $e) {
                    $t++;
                    goto get;
                }
            }

            if (!isset($result_api->id) || empty($result_api->id)) {
                dd('Login Error');
            }

            $usersh = array();
            $usersh['id'] = $result_api->id;
            $usersh['username'] = $result_api->username;

            $app_id = '18903324';
            $slg_user = OpenUser::firstOrCreate('soha', $usersh);
            //setlogin 
            if (!empty($slg_user->id)) {
                Auth::loginUsingId($slg_user->id);
            }

            //lastest played server
            $server_lastest_user = UtilHelper::gamePlaynow($app_id, $slg_user->id);

            UserHelper::logUserGame($slg_user->id, static::$game_id, 100000024);//100000024: soha
        }

        $servers = GameServices::getServerList(static::$game_id);
        // get list news
        $news = $this->getNews();

        // get lastest server user play        
        $data = ['listserver' => $servers, 'usersh' => $result_api, 'news' => $news, 'user' => $slg_user, 'server_lastest_user' => $server_lastest_user];
        return view('game.linhvuong.soha.listserver', ['data' => $data]);
    }

    private function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    private function parse_signed_request($signed_request, $secret) {
        //dd(explode('.', $signed_request, 2));
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // Adding the verification of the signed_request below
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    private function getLoginGame($server, $slg_user, $pid = 113) {
        $url = 'http://' . $server['domain_server'] . '.linhvuong.slg.vn/';
        $a = date('YmdHis', strtotime('+5 min'));
        $loginkey = "dgspjfdFfdsR234F433sdr3rd2d3f5sa43";
        $sign = md5($slg_user->id . $slg_user->name . $a . $pid . $loginkey);
        //$http = $login_ip;
        $url = $url . 'Interfaces/login_partner.aspx?uid=' . $slg_user->id . '&uname=' . $slg_user->name . '&ulgtime=' . $a . '&pid=' . $pid . '&sign=' . $sign;
        return ($url);
    }

    public function getNews() {

        $cache_key = 'lv_news4';
        $time = 0;
        if (!Cache::has($cache_key)) {

            $t = 0;
            get:
            if ($t < 3) {
                try {
                    $_request = cURL::newRequest('get', 'http://linhvuong.slg.vn/home/api_news')
                        ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                        ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                        ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                        ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                        ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

                    $response = $_request->send();

                    $str = $response->body;
                } catch (\Exception $e) {
                    $t++;
                    goto get;
                }
            }

            //$data = json_decode($str, false, 512, JSON_BIGINT_AS_STRING);
            if (!isset($str))
            {
                dd('Error');
            }
            
            $data = unserialize(base64_decode($str));
            $value = json_encode($data['tintuc']);
            Cache::add($cache_key, $value, $time);
        }

        $value = Cache::get($cache_key);
        $obj = json_decode($value);

        return $obj;
    }

    public function getNewscontent(Request $request) {
        $news_list = $this->getNews();
        $data = array();
        $user = array();
        $result_api = array();
        if (Auth::check()) {
            $user = Auth::user();
            $data['user'] = $user;
        }

        $signed_request = Input::get('signed_request');
        if (!empty($signed_request)) {
            //echo '$signed_request: '.$signed_request.'<br/>';

            /*
              9db070d1d9726d467f00d98e95a35e90
              secret: f98916230e62cdc01e54578a2aeae01f
             *              */
            $result = $this->parse_signed_request($signed_request, 'f98916230e62cdc01e54578a2aeae01f');
            if (!$result) {
                dd('Invalid Signature.');
                exit;
            }

            $t = 0;
            get:
            if ($t < 3) {
                try {
                    $_request = cURL::newRequest('get', 'http://soap.soha.vn/api/a/GET/me/info?access_token=' . $result['access_token'])
                        ->setOption(CURLOPT_SSL_VERIFYPEER, false)
                        ->setOption(CURLOPT_SSL_VERIFYHOST, false)
                        ->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false)
                        ->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
                        ->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

                    $response = $_request->send();

                    $result_api = json_decode($response->body);
                } catch (\Exception $e) {
                    $t++;
                    goto get;
                }
            }

            if (!isset($result_api->id) || empty($result_api->id)) {
                dd('Login Error');
            }

            $usersh = array();
            $usersh['id'] = $result_api->id;
            $usersh['username'] = $result_api->username;

            $app_id = '18903324';
            $slg_user = OpenUser::firstOrCreate('soha', $usersh);
            //setlogin 
            if (!empty($slg_user->id)) {
                Auth::loginUsingId($slg_user->id);
            }
        }


        $data['news_list'] = $news_list;
        if (is_object($result_api)) {
            $data['usersh'] = $result_api;
        }

        $id = Input::get('id');
        foreach ($news_list as $news) {
            if ($news->id == $id) {
                $data['news'] = $news;
                return view('game.linhvuong.soha.newscontent', ['data' => $data]);
            }
        }
        dd('Khong tim thay bai viet');
    }

    public function getPayment(Request $request) {
        $user = 0;
        // Get Scoin from Soha api
        $signed_request = Input::has('signed_request') ? Input::get('signed_request') : 0;

        if (Auth::check()) {
            $user = Auth::user();
        }

        if (is_object($user) && strtolower($user->provider) != 'soha') {
            return redirect('http://pay.slg.vn/topcoin/linh-vuong');
            exit();
        } else if (empty($signed_request) && is_object($user) && strtolower($user->provider) == 'soha') {
            return redirect('http://linhvuong.sohaplay.vn/payment');
            exit();
        }

        $user = Auth::user();
        $game = MerchantApp::where('slug', trim('linh-vuong'))->first();
        $servers = GameServices::getServerList($game->id);
        //dd($servers);
        $data = array();

        if (!empty($signed_request)) {


            $result = $this->parse_signed_request($signed_request, 'f98916230e62cdc01e54578a2aeae01f');
            if (!$result) {
                dd('Invalid Signature.');
                exit;
            }
            $url_api = 'https://soap.soha.vn/api/a/GET/pay/scoin?access_token=' . $result['access_token'];
            $response = file_get_contents($url_api);
            $result_api = json_decode($response);
            $data['scoin'] = $result_api->scoin;

            $result = $this->parse_signed_request($signed_request, 'f98916230e62cdc01e54578a2aeae01f');
            if (!$result) {
                dd('Invalid Signature.');
                exit;
            }
            $url_api = 'http://soap.soha.vn/api/a/GET/me/info?access_token=' . $result['access_token'];
            $response = file_get_contents($url_api);
            $result_api = json_decode($response);
            if (empty($result_api->id)) {
                dd('Login Error');
            }
            $usersh = array();
            $usersh['id'] = $result_api->id;
            $usersh['username'] = $result_api->username;

            $app_id = '18903324';
            $slg_user = OpenUser::firstOrCreate('soha', $usersh);
            //setlogin 
            if (!empty($slg_user->id)) {
                Auth::loginUsingId($slg_user->id);
            }
        } else {
            dd('error');
            exit();
        }

        $data['usersh'] = $result_api;
        $data['listserver'] = $servers;
        return view('game.linhvuong.soha.payment', ['data' => $data]);
    }

    public function getRedirectsohaform() {
        if (!Auth::check()) {
            dd('Login Error');
        }

        $user = Auth::user();
        $soha_user_id = $user->id;
        $appid = '18903324';
        $server_id = Input::get('server_id');
        $order_info = Input::get('order_info');
//        if ($soha_user_id != Input::get('userid')) {
//            die('user not match');
//        }
        // check registed user

        $r_uid = $user->id;
        $server_id = $server_id;
        $coin = 0;

        // update log and transfer to game.
        $game = MerchantApp::where('slug', trim('linh-vuong'))->first();
        $service = GameServices::createService($game->id);
        $result = $service->transfer($r_uid, $server_id, $r_uid . time(), $coin, 0);
        if ($result != 'success') {
            dd('Bạn chưa tạo nhân vật trên server này.');
            exit();
        }

        //create $order_info_2 send to sohaplatform
        $time = time();
        $rand = rand(1000, 999999);
        $order_info_2 = md5($soha_user_id . $server_id . $order_info . $time . $rand);

        // Log trans to db
        $arr_log = [
            'cpid' => '300000193',
            'userid' => $soha_user_id,
            'server_id' => $server_id,
            'channel_id' => 'soha',
            'item_price' => $order_info,
            'server_id' => $server_id,
            'order_id' => $order_info_2,
            'status' => 1,
        ];
        $rs = LogBuyItemSoha::logBuyItemStep1($arr_log);

        if (!$rs) {
            die('error 1');
        }
        $url = 'http://soap.soha.vn/dialog/pay?';
        $url .= 'app_id=9db070d1d9726d467f00d98e95a35e90';
        //$url .= '&redirect_uri=http://app.slg.vn/linhvuong/sohapayredirect?success=1'; // . $_REQUEST['redirect_uri'];
        $url .= '&redirect_uri=http://app.slg.vn/linhvuong/sohapaycallback'; // . $_REQUEST['redirect_uri'];
        $url .= '&order_info=' . $order_info_2;
        header('location: ' . $url);
        exit();
    }

    public function getSohapayredirect() {
        dd('Success');
    }

    public function getSohapaycallback() {

        $method = Input::get('method');
        $user_id = Input::get('user_id');
        $order_info = Input::get('order_info');
        $order_id_soha = Input::get('order_id');

        //if method is get order info -- STEP 1
        if ($method == 'payments_get_items') {
            //Redis::set('log_step3', json_encode(Input::all()));
            //order_info is the param created by game/app when user click to buy something in game
            //from order_info param, game/app retrieves info to create specific order 
            if (empty($order_info) && empty($user_id)) {
                die();
            }
            $log = LogBuyItemSoha::getItemByOrderInfo($order_info);

            //find item by userid and order_info
            if (is_int((int) $log->item_price) && (int) $log->item_price >= 0) {
                $scoin = $log->item_price;
                $id = $log->id;
                $gold = $scoin * 10;
                $order_details = array('item_id' => '00' . $id,
                    'title' => $gold . ' Vàng',
                    'description' => 'Mua ' . $gold . ' vàng bằng ' . $scoin . ' Scoin',
                    'image_url' => 'http://soha.op.slg.vn/assets/front/images/icon_gold.png',
                    'product_url' => '',
                    'price' => $scoin,
                    'data' => 'Mua ' . $gold . ' vàng bằng ' . $scoin . ' Scoin');
            }
            $rs = LogBuyItemSoha::logBuyItemStep2($order_info, $order_id_soha);
            //return order details to SOAP
            return response()->json($order_details);
        } else if ($method == 'payments_status_update') {  //-- STEP 2
            Redis::set('log_step4', json_encode(Input::all()));
            $order_status = Input::get('status');
            if ($order_status == 'settled') {
                //order is ok now, update the order with the $order_id
                //$rs = $gameConnection->logBuyItemStep3($user_id, $order_info, $order_id_soha);
                // update to game
                $order_id = $order_id_soha;
                $appid = '18903324';
                $log = LogBuyItemSoha::getLogBuySohaTransid($order_id, 2);
                if (!(is_object($log) && $log->id)) {
                    die('error 5');
                }
                $result = array('status' => 'settled');
                return response()->json($result);
            }
        } else {
            // Step 3, check  success or fail
            $order_id = Input::get('order_id');
            $status = Input::get('status');
            if ($status == 'settled' && !empty($order_id)) {
                $log = LogBuyItemSoha::getLogBuySohaTransid($order_id, 2);
                if (is_object($log) && !empty($log->userid) && !empty($log->item_price)) {
                    $r_uid = $log->userid;
                    $server_id = $log->server_id;
                    $coin = $log->item_price * 10;
                    // check user login

                    if (Auth::check()) {
                        $user = \Auth::user();
                    } else {
                        $user = \Auth::loginUsingId($log->userid);
                    }

                    // update log and transfer to game.
                    $game = MerchantApp::where('slug', trim('linh-vuong'))->first();
                    $service = GameServices::createService($game->id);
                    $result = $service->transfer($r_uid, $server_id, $r_uid . time(), $coin, 0);
                    if ($result === false) {
                        dd('Người chơi không tồn tại trên máy chủ.');
                    } elseif ($result == 'success') {
                        // update status trans
                        $log->status = 3;
                        $log->save();

                        $cpid = '300000193';
                        $arr = [
                            'cpid' => $cpid,
                            'uid' => $r_uid,
                            'telco' => "1",
                            'clientid' => 1001, // linvuong app_id
                            'serial' => "1",
                            'amount' => $coin * 100,
                            'code' => "1",
                        ];
                        $revenuelog = new RevenueLogHelper;
                        $revenuelog->setRevenue($arr);

                        // new log

                        //$order_id = $data->soha_order_info;
                        $log = \App\Models\LogBuyItemSoha::firstOrNew(['soha_order_info' => $order_id]);
                        $log->cpid = $cpid;
                        $log->userid = $r_uid;
                        $log->item_price = $coin;
                        $log->server_id = $server_id;
                        $log->soha_order_info = $order_id;
                        $log->status = 3;
                        $log->channel_id = 'soha';
                        $log->request_time = date('Y-m-d H:i:s', time());
                        $log->save();


                        //*************ghi log**********************
                        // todo log to revenue game
                        dd('Nạp Coin thành công.');
                    }
                } else {
                    dd('Loi giao dich, hoac giao dich da duoc thuc hien.');
                }
            }
        }
    }

    public function getTest() {
        $user = Auth::loginUsingId(5364924);
        $cpid = Merchant_app_cp::getCpidUser($user->provider, 1001);
        dd($cpid);
    }

}
