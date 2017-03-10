<?php

namespace App\Http\Controllers\App;

use App\Models\MerchantApp;
use Cache;
use Validator;
use App\Http\Controllers\Controller;
use Facebook;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Facebook\Exceptions\FacebookSDKException;
use Session, Illuminate\Http\Request;

class AppController extends Controller
{

    public function Index()
    {
        dd('keke');
        return;
    }

    public function get_index()
    {
        dd('keke');
        return;
    }

    public function getOpfblogin(LaravelFacebookSdk $fb)
    {
        $scope = array('user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me');
        $loginUrl = $login_url = $fb->getLoginUrl($scope);
        echo ' <script type="text/javascript">
                window.parent.location.href="' . $loginUrl . '";
             </script>';
        exit;
    }

    public function fbonepiece(LaravelFacebookSdk $fb)
    {
        try {
            $token = $fb->getCanvasHelper()->getAccessToken();
        } catch (FacebookSDKException $e) {
            // Failed to obtain access token
            dd($e->getMessage());
        }

        // $token will be null if the user hasn't authenticated your app yet
        if (!$token) {
            dd('chua login');
        } else {
            if (!$token->isLongLived()) {
                // OAuth 2.0 client handler
                $oauth_client = $fb->getOAuth2Client();

                // Extend the access token.
                try {
                    $token = $oauth_client->getLongLivedAccessToken($token);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    dd($e->getMessage());
                }
            }

            $fb->setDefaultAccessToken($token);

            // Save for later
            Session::put('fb_user_access_token', (string)$token);

            // Get basic info on the user from Facebook.
            try {
                $response = $fb->get('/me?fields=id,name,email');
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                dd($e->getMessage());
            }

            // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
            $facebook_user = $response->getGraphUser();
            dd($facebook_user);
        }
        return;
    }

    public function getAdGameList(Request $request)
    {
        $games = Cache::rememberForever('active_game_list', function () {
            $_games = MerchantApp::where('status', 1)->get();
            return $_games->toArray();
        });

        return view('ads.games', ['games' => $games, 'fb' => $request->get('fb', 'facebook')]);
    }
}
