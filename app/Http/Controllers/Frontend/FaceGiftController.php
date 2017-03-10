<?php

namespace App\Http\Controllers\Frontend;


use GuzzleHttp\Subscriber\Redirect;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App, Cache, cURL;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;
use Session;
use App\Models\MerchantApp;
use App\Models\Event_game;
use App\Models\GiftCodeServer;
use App\Models\MerchantAppConfig;
use Illuminate\Support\Facades\Auth;


class FaceGiftController extends Controller
{
    private $onepiece_fbconfig = array(
        'app_id' => '748333908644144',
        'app_secret' => '0084406e9dfefe8d56b403e38674ac8b',
        'default_graph_version' => 'v2.5',
        'enable_beta_mode' => true,
        'http_client_handler' => 'guzzle');

    private function createAppFBOnepiece()
    {
        $config = $this->onepiece_fbconfig;
        App::bind('fbonepiece', function ($app) use ($config) {
            if (!isset($config['persistent_data_handler'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }
            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }
            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });
        return App::make('fbonepiece');
    }
    public function LoginFacebook(Request $request,$id){
        $event = Event_game::find($id);
        if($event){
            $game = GiftCodeServer::select('gift_game_servers.*','merchant_app.*')
                ->rightjoin('merchant_app','merchant_app.id','=','gift_game_servers.game_id')
                ->where('gift_game_servers.event_id',$event->id)->where('merchant_app.status',1)
                ->first();
            if($game){
                $fb = $this->createAppFBOnepiece();
                $scope = array('email', 'user_likes');
                $loginUrl = $fb->getLoginUrl($scope,route('call.back',['id'=>$event->id]));
                return redirect($loginUrl);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }
    }

    public function FacebookCallback(Request $request,$id)
    {
        $event_id = Event_game::find($id);
        $game = GiftCodeServer::select('gift_game_servers.*','merchant_app.*')
            ->rightjoin('merchant_app','merchant_app.id','=','gift_game_servers.game_id')
            ->where('gift_game_servers.event_id',$event_id->id)->where('merchant_app.status',1)
            ->first();
        $server_id = GiftCodeServer::where('event_id','=',$event_id->id)
            ->where('game_id',$game->game_id)
            ->groupby('server_id')
            ->get();
        $in = array();
        foreach ($server_id as $value){
            array_push($in,$value->server_id);
        }
        $server = MerchantAppConfig::wherein('serverid',$in)->where('appid',$game->game_id)->where('status_server',1)->get();
        $our_page_id = $game->id_fangage;
        $fb = $this->createAppFBOnepiece();
        try {
            $token = $fb->getAccessTokenFromRedirect(route('call.back',['id'=>$event_id->id]));
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }
        if (!$token) {
            $helper = $fb->getRedirectLoginHelper();
            if (!$helper->getError()) {
                abort(403, 'Unauthorized action.');
            }
            dd(
                $helper->getError(),
                $helper->getErrorCode(),
                $helper->getErrorReason(),
                $helper->getErrorDescription()
            );
        }
        if (!$token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauth_client = $fb->getOAuth2Client();
            try {
                $token = $oauth_client->getLongLivedAccessToken($token);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                dd($e->getMessage());
            }
        }
        $fb->setDefaultAccessToken($token);
        Session::put('fb_user_access_token', (string)$token);
        try {
            $response = $fb->get('/me/likes?fields=id');
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }
        $likes = $response->getGraphEdge()->asArray();
        foreach ($likes as $item){
            if($item['id'] == $our_page_id ){
            // ckeck like fan Page facebook ok
                // kiểm tra tiếp xem đã share events chưa
                if (isset($event_id->link_share)){
                    if(Cache::has('share_event_'.Auth::id())){
                        Cache::put('gift-code_'.Auth::id().'_'.$event_id->id.'_'.$game->game_id,'',30);
                        Cache::forget('error_share'.Auth::id());
                        Cache::forget('error_like'.Auth::id());
                        return redirect()->route('event.detail',['id'=>$event_id->id]);
                    }else{
                        Cache::put('error_share'.Auth::id(),'',30);
                        Cache::forget('error_like'.Auth::id());
                        return redirect()->route('event.detail',['id'=>$event_id->id]);
                    }
                }else{
                    Cache::put('gift-code_'.Auth::id().'_'.$event_id->id.'_'.$game->game_id,'',30);
                    Cache::forget('error_like'.Auth::id());
                    return redirect()->route('event.detail',['id'=>$event_id->id]);
                }
            }else{
                // ckeck like facebook false
                Cache::put('error_like'.Auth::id(),'',30);
                return redirect()->route('event.detail',['id'=>$event_id->id]);
                break;
            }
        }
    }
}
