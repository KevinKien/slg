<?php

namespace App\Http\Controllers\App;

use App\Helpers\UserHelper;
use App\Models\User;
use Validator, cURL;
use App\Http\Controllers\Controller;
use App\Models\UserGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use \SESSION;
use \App, Cache;
use App\Helpers\Games\GameServices;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\MailHelper;

class TamQuocTruyenKyController extends Controller {

    public static $game_id = 18903335;
    public $client_id = '2055657558';
    public $client_secret = 'LCgGQqldbsCDxnS4DrDX';
    public $redirect_uri = 'https://app.slg.vn/tamquoctruyenky/FBcallback';

    const AUTHORIZE_URL = 'http://id.slg.vn/oauth/authorize';
    const REGISTER_URL = 'http://id.slg.vn/oauth/register';
    const ACCESS_TOKEN_URL = 'http://id.slg.vn/oauth/access_token';
    const API_URL = 'http://api.slg.vn';

    public function __construct() {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    private $tamquoc_fbconfig = array(
        'app_id' => '1107986715975079',
        'app_secret' => 'e30db2f4bceb0c67f36a181c10788652',
        'callback' => 'https://apps.facebook.com/tamquoctruyenkyslg',
        'default_graph_version' => 'v2.8',
        'enable_beta_mode' => true,
        'http_client_handler' => 'guzzle');

    public function Index() {
        return;
    }

    public function getTamquocfblogin(LaravelFacebookSdk $fb) {
        $scope = array('email', 'read_stream', 'user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me');
        $loginUrl = $login_url = $fb->getLoginUrl($scope);
        echo ' <script type="text/javascript">
                window.parent.location.href="' . $loginUrl . '";
             </script>';
        exit;
    }

    private function createAppFBTamquoc() {
        $config = $this->tamquoc_fbconfig;

        App::bind('fbtamquoc', function ($app) use ($config) {
            if (!isset($config['persistent_data_handler'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }

            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });

        return App::make('fbtamquoc');
    }

    public function fbtamquoctruyenky(Request $request) {

        $config = $this->tamquoc_fbconfig;

        $fb = $this->createAppFBTamquoc();

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

                $user = User::create($newuser);

                if (isset($facebook_user['email']) && !empty($facebook_user['email'])) {
                    MailHelper::sendMailWelcome($user, $pass);
                }
            }

            if ($user->active == 0)
            {
                dd('Tài khoản của bạn đang bị khóa.');
            }

            if (Auth::loginUsingId($user->id)) {

                $dataservers = GameServices::getServerList(static::$game_id);
                $servers = array_reverse($dataservers);
                $server_user = UtilHelper::gamePlaynow(static::$game_id, $user->id);
                $count = count($servers);
                UserHelper::logUserGame($user->id, static::$game_id, 100000011);
                
                return view('fbapp.tamquoc.serverlist', ['user' => $user, 'servers' => $servers,'count' => $count , 'server_user' => $server_user]);
            } else {
                dd('Authen error');
            }
        }
        return;
    }

    public function getCheckuser(Request $request) {
        if (Auth::check()) {
            echo ' <script type="text/javascript">
                    window.parent.location.href="https://app.slg.vn/tamquoctruyenky/login_slg";
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
                header('location: ' . '//apps.facebook.com/fbtamquoctruyenky/login_slg');
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

    public function getSlg(Request $request) {
        $server_id = (int) trim($request->get('server'));

        if (!Auth::check() || !is_int($server_id)) {
//            dd('Bạn chưa đăng nhập, vui lòng truy cập tamquoctruyenky.vn!');
            return "<script>window.top.location.href = 'http://tamquoctruyenky.vn';</script>";
        }

        $user = Auth::user();

//        if (!$user->is('administrator')) {
//            dd('Hẹn bạn quay lại lúc 10h hôm nay :)');
//            return;
//        }

        $servers = [];

        $_servers = GameServices::getServerList(static::$game_id);

        foreach ($_servers as $server)
        {
            $servers[] = $server['serverid'];
        }

        if (!$user->is('administrator') && !in_array($server_id, $servers))
        {
            return "<script>window.top.location.href = 'http://tamquoctruyenky.vn';</script>";
        }

        $coins = Input::has('coins') ? Input::get('coins') : 0;

        $service = GameServices::createService(static::$game_id);

        if (empty($coins)) {
            $get_game_url = $service->getLoginUrl($user->id, $server_id);

            UserHelper::logUserGame($user->id, static::$game_id, 0, $server_id);
        } else {
            $get_game_url = $service->transfer($user->id, $server_id, rand(1000000, 9999999999999), $coins);
        }

        UtilHelper::gamePlaynow(static::$game_id, $user->id, $server_id);

        return view('game.tamquoc.gameserver', ['url' => $get_game_url, 'server_id' => $server_id]);
    }
}