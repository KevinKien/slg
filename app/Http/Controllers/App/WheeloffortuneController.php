<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Models\UserGame;
use App\Models\GiftItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use \SESSION;
use \App;
use App\Models\MerchantApp;
use App\Helpers\Games\GameServices;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\LaravelPersistentDataHandler;
use SammyK\LaravelFacebookSdk\LaravelUrlDetectionHandler;
use App\Helpers\Logs\UtilHelper;
use App\Helpers\MailHelper;
use Illuminate\Support\Facades\Cache;
use DB,DateTime;
class WheeloffortuneController extends Controller
{

    public $client_id = '7131260539';
    public $client_secret = 'iHB3V5yjniSu4iodQdjW';
    public $redirect_uri = 'https://app.slg.vn/wheeloffortune/FBcallback';

    const AUTHORIZE_URL = 'http://id.slg.vn/oauth/authorize';
    const REGISTER_URL = 'http://id.slg.vn/oauth/register';
    const ACCESS_TOKEN_URL = 'http://id.slg.vn/oauth/access_token';
    const API_URL = 'http://api.slg.vn';

    public function __construct()
    {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    private $wheeloffortune_fbconfig = array(
        'app_id' => '776842395785292',
        'app_secret' => '539ad650611bd1e86802f51d40bdfcd7',
        'callback' => 'https://apps.facebook.com/wheeloffortuneslg/',
        'default_graph_version' => 'v2.4',
        'enable_beta_mode' => true,
        'http_client_handler' => 'guzzle');

    public function Index()
    {
        return;
    }

    public function getwheeloffortunefblogin(LaravelFacebookSdk $fb)
    {
        $scope = array('email', 'read_stream', 'user_friends', 'read_custom_friendlists', 'user_friends', 'user_about_me');
        $loginUrl = $login_url = $fb->getLoginUrl($scope);
        echo ' <script type="text/javascript">
                window.parent.location.href="' . $loginUrl . '";
             </script>';
        exit;
    }

    private function createAppFBwheeloffortune()
    {

        $config = $this->wheeloffortune_fbconfig;

        App::bind('fbwheeloffortune', function ($app) use ($config) {
            if (!isset($config['persistent_data_handler'])) {
                $config['persistent_data_handler'] = new LaravelPersistentDataHandler($app['session.store']);
            }

            if (!isset($config['url_detection_handler'])) {
                $config['url_detection_handler'] = new LaravelUrlDetectionHandler($app['url']);
            }

            return new LaravelFacebookSdk($app['config'], $app['url'], $config);
        });

        return App::make('fbwheeloffortune');
    }

    public function fbwheeloffortuneslg(Request $request)
    {

        $config = $this->wheeloffortune_fbconfig;
        $fb = $this->createAppFBwheeloffortune();

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
            if (!$token->isLongLived()) {
                // OAuth 2.0 client handler
                $oauth_client = $fb->getOAuth2Client();

                // Extend the access token.
                try {
                    $token = $oauth_client->getLongLivedAccessToken($token);
                } catch (FacebookSDKException $e) {
                    dd($e->getMessage());
                }
            }

            $fb->setDefaultAccessToken($token);

            // Get basic info on the user from Facebook.
            try {
                $response = $fb->get('/me?fields=id,name,email');
            } catch (FacebookSDKException $e) {
                dd($e->getMessage());
            }

            // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
            $facebook_user = $response->getGraphUser();
            //dd($facebook_user);

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
            $usertest = $facebook_user['email'];
            $facebookid = $facebook_user['id'];
            $value = DB::table('wheel_item')
                    ->select('image_item')
                    ->where('is_use',1)
                    ->get();
            $image = $value[0]->image_item;
//            print_r(1);die;
            //dd($user);
            if (Auth::loginUsingId($user->id)) {
//                $game = MerchantApp::where('slug', 'wheel-of-fortune')->first();
//                //l?u b?ng danh sách game c?a ng??i ch?i
//                $user_game = UserGame::firstOrNew(['uid' => $user->id, 'app_id' => $game->id]);
//                $user_game->updated_at = date('Y-m-d H:i:s');
//                $user_game->save();
//
//                $servers = GameServices::getServerList($game->id);
//                $server_user = UtilHelper::gamePlaynow($game->id, $user->id);
//                print_r(1);die;
                return view('fbapp.wheeloffortune.serverlist', compact('usertest','facebookid','image'));
            } else {
                dd('Authen error');
            }
        }
        return;
    }

    public function getCheckuser(Request $request)
    {
        if (Auth::check()) {
            echo ' <script type="text/javascript">
                    window.parent.location.href="https://app.slg.vn/wheeloffortune/login_slg";
                 </script>';
            exit;
        }
        dd('error');
        return;
    }

    public function login_slg()
    {
        //$slgOAuth = new SlgOAuth($client_id, $client_secret, $ridirect_uri);
        $url_login = $this->getLoginUrl();
        print_r($url_login);
        die;
        header('location: ' . $url_login);
        exit();
    }

    public function getFBcallback()
    {
        $code = $_GET['code'];
        $site = 'http://slg.vn/demo/authen';

        if (!empty($code)) {
            $slgOAuth = new SlgOAuth($this->client_id, $this->client_secret, $this->redirect_uri);

            $response = $slgOAuth->getAccessToken($code);
            if (isset($response->error) && $response->error == 'invalid_request') {
                header('location: ' . '//apps.facebook.com/fbwheeloffortune/login_slg');
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

    public function getLoginUrl()
    {
        $response_type = 'code';
        if (empty($this->client_id) || empty($this->client_secret) || empty($this->redirect_uri)) {
            return FALSE;
        }
        $url = self::AUTHORIZE_URL . '?client_id=' . $this->client_id .
            '&redirect_uri=' . $this->redirect_uri .
            '&response_type=' . $response_type;
        return $url;
    }

    public function getAccessToken($code)
    {
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

//Phương thức store trả về kết quả vòng quay lên view vòng quay
    public function store(){
        $data_ids = $_REQUEST['data_ids'];
        $value =  DB::table('wheel_item')
                ->select('id','item','item_number','turn_dial','item_quantity')
                ->where('is_use',1)
                ->get();
        $count_dial = DB::table('gift_item')
            ->select(DB::raw('count(item) as numberitem'))
            ->where('facebookid',$data_ids)
            ->where('created_at', 'like',date('Y-m-d').'%' )
            ->get();
        $count = $value[0]->turn_dial - $count_dial[0]->numberitem;
        //$count giá trị số lượt quay còn lại
        $result = null;
        $result1 =  $this->getRandom((array)json_decode($value[0]->item));
        //$result1 giá trị quay random
        if($result1 != null){
        $nameitem = explode("_", $result1);
        $giftcode = DB::table('gift_code')
            ->select('id','giftcode')
            ->where('id_wheel',$value[0]->id)
            ->where('item',$nameitem[0])
            ->where('is_use',0)
            ->first();
        if($giftcode !=null){
            $code = $giftcode->giftcode;
        }else{
            $code = "Chúc bạn may mắn lần sau";
        }
        //$code giá trị gift code nhận đc
        $position = $this->findarrayindex($result1,(array)json_decode($value[0]->item));
        //$position giá trị vị trí item trong list item
        $rounds = mt_rand(10,14);
        //$rounds giá trị random số vòng quay
        $number = count((array)json_decode($value[0]->item));
        //$number giá trị tổng số item có trên vòng quay
        $degrees = mt_rand(($position-1)*(360/$number)+1,$position*(360/$number)-1);
        //$degrees giá trị góc quay trong khoảng vị trí item mình trả về (VD item vị trí 0 thì random trong khoảng từ 1 độ đến 44 độ)
        if($count == 0){
            $result =  ["degreesitem" => $degrees , "resultitem" => $nameitem[0], "rounds" => $rounds, "count" => $count-1];
        }else{
        $result =  ["degreesitem" => $degrees , "resultitem" => $nameitem[0], "rounds" => $rounds, "count" => $count-1,"giftcode" => $code];
        $time = time();
        $giftitem = GiftItem::firstOrNew(['facebookid' => $data_ids, 'item' => $nameitem[0],'gift_code' => $code , 'created_at' => date('Y-m-d H:i:s',$time)]);
        $giftitem->save();
        //sau mỗi lần quay lưu lại item quay đc của người dùng
        $this->updatewheel($result1,(array)json_decode($value[0]->item_quantity),$value[0]->id,(array)json_decode($value[0]->item));
        if($giftcode !=null){
            $this->updateGiftitem($giftcode->id,$data_ids);
            //cập nhật lại trạng thái của gift code từ chưa dùng sang đã dùng
        }
        }
        }else{
        }
        echo json_encode($result);

//        var degrees = game.rnd.between(45, 90);
//        // before the wheel ends spinning, we already know the prize according to "degrees" rotation and the number of slices
//        prize = slices - 1 - Math.floor(90 / (360 / slices));
//        $rounds = mt_rand(10,14);
//        $degrees = mt_rand();
    }

    public function store1(){
        $data_ids = $_REQUEST['data_ids'];
        $value =  DB::table('wheel_item')
                ->select('item','item_number','turn_dial')
                ->where('is_use',1)
                ->get();
        $count_dial = DB::table('gift_item')
            ->select(DB::raw('count(item) as numberitem'))
            ->where('facebookid',$data_ids)
            ->where('created_at', 'like',date('Y-m-d').'%' )
            ->get();
        $count = $value[0]->turn_dial - $count_dial[0]->numberitem;
        if($count >= 0){
            $result =  ["count" => $count];
        }else{
            $result =  ["count" => 0];
        }
        echo json_encode($result);
    }
// Phương thức store2 lấy về lịch sử quay của người dùng trong trong 2 ngày
    public function store2(){
        $data_ids = $_REQUEST['data_ids'];
        $time = time();
        $dateto = date('Y-m-d',$time).' 23:59:59';
        $date = new DateTime(date('Y-m-d H:i:s',$time));
        $date->modify('-1 day');
        $datefrom = $date->format('Y-m-d').' 00:00:00';

        $demo = DB::table('gift_item')
            ->select('gift_code', 'item', 'created_at')
            ->where('facebookid', $data_ids)
            ->where('created_at', '>=', $datefrom)
            ->where('created_at', '<=', $dateto)
            ->get();
        echo json_encode($demo);
    }

    public function updatewheel($tofindKey,$quantityArray,$id,$itemArray){
        foreach($quantityArray as $key => $value){
            $quantity = (int)$value;
            if($key == $tofindKey){
                $quantityArray[$key] = --$quantity;
                if($quantity == 0){
                    foreach($itemArray as $key1 => $value1){
                        if($key1 == $tofindKey){
                            $itemArray[$key1] = 0;
                            DB::table('wheel_item')->where('id', $id)->update(
                                ['item' => json_encode($itemArray),
                                 'item_quantity' => json_encode($quantityArray)
                                ]);
                        }
                    }
                }
                else{
                DB::table('wheel_item')->where('id', $id)->update(
                    ['item_quantity' => json_encode($quantityArray)
                    ]);
                }
            }
        }
    }

    public function updateGiftitem($idgiftcode,$iduser){
        $time = time();
        DB::table('gift_code')
            ->where('id', $idgiftcode)
            ->update(['is_use' => 1,
                    'id_user' => $iduser,
                    'updated_at' => date('Y-m-d H:i:s',$time)
                ]);
    }

    function findarrayindex($tofindKey, $inArray){
        $index = 1;
        foreach ($inArray as $key => $value) {
            if($key == $tofindKey){
                return $index;
            }
            $index++;
        }
    }

    public function getRandom(array $weightedValues) {
        if((int)array_sum($weightedValues) > 0){
        $rand = mt_rand(1, (int) array_sum($weightedValues));
        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
        }else{
            return null;
        }
    }
//    public function getSlg(Request $request)
//    {
//        if (!Auth::check()) {
//            dd('login error');
//            return;
//        }
//        $user = Auth::user();
//        $server_id = Input::get('server');
//        $game = MerchantApp::where('slug', trim('one-piece-online'))->first();
//        $service = GameServices::createService($game->id);
//        $get_game_url = $service->getLoginUrl($user->id, $server_id);
//        $servers = GameServices::getServerList($game->id);
//        $server_user = UtilHelper::gamePlaynow('17054245', $user->id, $server_id);
//        return view('game.onepiece.gameserver', ['url' => $get_game_url, 'servers' => $servers, 'server_id' => $server_id]);
//    }

    public function test()
    {
        dd('test');
    }

}