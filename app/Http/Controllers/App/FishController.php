<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use Validator, Cache;
use App\Http\Controllers\Controller;
use cURL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use \SESSION;
use \App;
use App\Helpers\Games\GameServices;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\MailHelper;

class FishController extends Controller {

    public $client_id = '8633283045';
    public $client_secret = 'qQTPEmOXhKSi2jkVHRMH';
    public $redirect_uri = 'https://app.slg.vn/fish/FBcallback';

    const AUTHORIZE_URL = 'http://id.slg.vn/oauth/authorize';
    const REGISTER_URL = 'http://id.slg.vn/oauth/register';
    const ACCESS_TOKEN_URL = 'http://id.slg.vn/oauth/access_token';
    const API_URL = 'http://api.slg.vn';

    public function __construct() {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    private $onepiece_fbconfig = array(
        'app_id' => '1957592584466475',
        'app_secret' => '303bf2b8363ecb3026e5c9004a41fb4b',
        'callback' => 'https://apps.facebook.com/1957592584466475',
        'default_graph_version' => 'v2.5',
        'enable_beta_mode' => true,
        'http_client_handler' => 'guzzle');

    public function Index() {
        return;
    }

    public function getOpfblogin(LaravelFacebookSdk $fb) {
        $scope = array('email', 'read_stream', 'user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me', 'publish_actions');
        $loginUrl = $login_url = $fb->getLoginUrl($scope);
        echo ' <script type="text/javascript">
                window.parent.location.href="' . $loginUrl . '";
             </script>';
        exit;
    }

    private function createAppFBFish() {
        $config = $this->onepiece_fbconfig;

        App::bind('fbfish', function ($app) use ($config) {
            if (!isset($config['persistent_data_handler'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }

            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });

        return App::make('fbfish');
    }

    public function FbFish_slg() {
        $config = $this->onepiece_fbconfig;
        $fb = $this->createAppFBFish();

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
//
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
            //dd($facebook_user);

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
                return view('fbapp.fish.serverlist', ['user' => $user]);
            } else {
                dd('Authen error');
            }
        }
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
                header('location: ' . '//apps.facebook.com/FBfishslg/login_slg');
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

    public function getTest() {
        if (Auth::check()) {
            $user = \Auth::user();
        } else {
            dd('login error');
        }

        $uid = $user->id;
        $server_id = 1;
        $coin = 100;
        $service = GameServices::createService(8633283045);
        $result = $service->transfer($uid, $server_id, $uid . time(), $coin);
        dd('test fish app');
    }

}
