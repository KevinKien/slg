<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use Validator, cURL;
use App\Http\Controllers\Controller;
use App\Models\UserGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use \SESSION;
use \App, Cache;
use App\Models\MerchantApp;
use App\Helpers\Games\GameServices;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\MailHelper;

class MangaController extends Controller {

    public $client_id = '6643224839';
    public $client_secret = 'YCM8Ym23Ko3iTBUR2u28';
    public $redirect_uri = 'https://app.slg.vn/manga/FBcallback';

    const AUTHORIZE_URL = 'http://id.slg.vn/oauth/authorize';
    const REGISTER_URL = 'http://id.slg.vn/oauth/register';
    const ACCESS_TOKEN_URL = 'http://id.slg.vn/oauth/access_token';
    const API_URL = 'http://api.slg.vn';

    public function __construct() {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    private $mangaheros_fbconfig = array(
        'app_id' => '1683270851913689',
        'app_secret' => 'a22b57dccf16bd50563535b2a0284f0c',
        'callback' => 'https://apps.facebook.com/mangaheros',
        'default_graph_version' => 'v2.5',
        'enable_beta_mode' => true,
        'http_client_handler' => 'guzzle');

    public function getMangafblogin(LaravelFacebookSdk $fb) {
        $scope = array('email', 'read_stream', 'user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me');
        $loginUrl = $login_url = $fb->getLoginUrl($scope);
        echo ' <script type="text/javascript">
                window.parent.location.href="' . $loginUrl . '";
             </script>';
        exit;
    }

    private function createAppFBManga() {
        $config = $this->mangaheros_fbconfig;

        App::bind('fbmanga', function ($app) use($config) {
            if (!isset($config['persistent_data_handler'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }

            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });

        return App::make('fbmanga');
    }

    public function fbmangaheros(Request $request) {

        $config = $this->mangaheros_fbconfig;
        $fb = $this->createAppFBManga();


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

            //dd($user);
            if (Auth::loginUsingId($user->id)) {
                $slug = 'manga-dai-chien';

                $apps = Cache::get('active_app_list');
                if (isset($apps[$slug])) {
                    $game_id = $apps[$slug];
                } else {
                    $game = MerchantApp::where('slug', $slug)->first();
                    $game_id = $game->id;
                }

                //lưu bảng danh sách game của người chơi
                $user_game = UserGame::firstOrNew(['uid' => $user->id, 'app_id' => $game_id]);
                $user_game->updated_at = date('Y-m-d H:i:s');
                $user_game->save();

                $servers = GameServices::getServerList($game_id);
                $server_user = UtilHelper::gamePlaynow($game_id, $user->id);
                return view('fbapp.manga.serverlist', ['user' => $user, 'servers' => $servers, 'server_user' => $server_user]);
            } else {
                dd('Authen error');
            }
            //print_r(1);die;
        }
        return;
    }

    public function getCheckuser(Request $request) {
        if (Auth::check()) {
            echo ' <script type="text/javascript">
                    window.parent.location.href="https://app.slg.vn/manga/login_slg";
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
                header('location: ' . '//apps.facebook.com/mangaheros/login_slg');
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
        dd($request);
        return;
    }

    public function getSlg(Request $request) {
        if (!Auth::check()) {
            dd('login error');
            return;
        }
        $user = Auth::user();
        $server_id = Input::get('server');
        $game = MerchantApp::where('slug', trim('manga-dai-chien'))->first();
        $service = GameServices::createService($game->id);

        //get real uid from partner
        $uid = $this->getRealUid($user);
        $get_game_url = $service->getLoginUrl($uid, $server_id);
        $servers = GameServices::getServerList($game->id);
        $server_user = UtilHelper::gamePlaynow('18903324', $uid, $server_id);

        return view('game.manga.gameserver', ['url' => $get_game_url, 'servers' => $servers, 'server_id' => $server_id]);
    }

    private function getRealUid($user) {
        if (is_object($user)) {
            $id = 0;
            switch ($user->provider) {
                case 'fpay':
                    $id = $user->fid;
                    break;
                default :
                    $id = 'slg' . $user->id;
            }

            return $id;
        }
        return 'slg' . $user->id;
    }

}
