<?php
/**
 * Created by PhpStorm.
 * User: vuong
 * Date: 11/1/2016
 * Time: 11:29 AM
 */

namespace App\Http\Controllers\App;

use App\Libraries\ZingCredits\ZC2_CallbackResultData;
use App\Helpers\UserHelper;
use CommonHelper, App\Models\User;
use Illuminate\Http\Request, Auth, App\Helpers\Games\GameServices, App\Helpers\Logs\UtilHelper;
use Session, App\Helpers\OpenUser, Feeds, Cache, Log, App\Libraries\ZingSDK\ZME_Me;
use App\Http\Controllers\Controller, App\Models\MerchantAppConfig, cURL;
use App\Libraries\ZingCredits\ZC2_BalanceData, App\Libraries\ZingCredits\ZCypher2Lib, App\Libraries\ZingCredits\ZC2_BillingData;

class OnePieceZingController extends Controller
{
    public static $app_id = 17054245;
    public static $partner_id = 100000009;
    public static $zing_url = 'http://me.zing.vn/apps/onepieceonline';
    public static $zing_feed_url = 'http://blog.zing.vn/jb/rss/onepiecefpay';
    public static $cache_news = '17054245100000009-news';
    public static $cache_new_server = '17054245100000009-servers-new';
    public static $app_name = 'onepieceonline';
    public static $zing_credits_key1 = 'hgH8pj8+bWHiI4+5ddUOLVDngYZFjH7y';
    public static $zing_credits_key2 = 'a7H6phQvjX41xTAf4B14nPyX5PPWpo+O';
    public static $zing_billing_url = 'http://pay-credits-me.zing.vn/billing/';
    public static $config = [
        'appname' => 'onepieceonline',
        'apikey' => 'b274abcceb744e9e8587e8c9233a6eb8',
        'secretkey' => 'f2171a2334f6427d89a3431a72e0bb47',
        'env' => 'production'
    ];

    public function getIndex(Request $request)
    {
        $src = '';
        $zing_url = static::$zing_url;

        if ($request->session()->has('zing_user')) {
            $zing_user = $request->session()->get('zing_user');
            $slg_id = $zing_user['slg_id'];

            $src = $request->session()->get('src');

            $name = empty($zing_user['fullname']) ? $zing_user['username'] : $zing_user['fullname'];

            $recent_server_id = UtilHelper::gamePlaynow(static::$app_id, $slg_id, 0, static::$partner_id);
        }

        $news = Cache::remember(static::$cache_news, 60, function () {
            $feed = Feeds::make(static::$zing_feed_url, 8, true); // true: if RSS Feed has invalid mime types, force to read it.

            $articles = [];

            if ($feed->get_item_quantity() > 0) {
                foreach ($feed->get_items() as $article) {
                    $articles[] = [
                        'title' => $article->get_title(),
                        'link' => $article->get_permalink(),
                    ];
                }
            }

            return $articles;
        });
            
           /* $new_servers = Cache::remember(static::$cache_new_server, 60, function () {
            $servers = MerchantAppConfig::getNewServerList(static::$app_id, 1, static::$partner_id);
            return $servers->toArray();
            }); */

        $servers = GameServices::getServerList(static::$app_id, static::$partner_id);

        return view('game.onepiece.zing.main', compact('zing_url', 'src', 'url', 'servers', 'recent_server_id', 'news', 'new_servers', 'name'));
    }

    public function getPlay(Request $request)
    {
        $zing_user = $request->session()->get('zing_user');
        $name = $zing_user['username'];
        if($name != "quynhthao91"  && $name != "thanhkiemthe001" && $name != "maulekoho" && $name != "adn121212" && $name != "alonepark" && $name != "seloto"){            
            $request->session()->put('alert', 'Chúng tôi đang tiến hành cài đặt máy chủ game mới, vui lòng quay lại sau!');
            return redirect()->route('onepiece-zing.index');            
        }        
        

        if (!$request->session()->has('zing_user')) {
            return redirect()->route('onepiece-zing.index');
        }

        if ($request->has('_srvid')) {
            $zing_user = $request->session()->get('zing_user');

            $slg_id = $zing_user['slg_id'];

            $recent_server_id = UtilHelper::gamePlaynow(static::$app_id, $slg_id, 0, static::$partner_id);

            $servers = GameServices::getServerList(static::$app_id, static::$partner_id);

            $server_id = $request->get('_srvid');

            UtilHelper::gamePlaynow(static::$app_id, $slg_id, $server_id, static::$partner_id);

            $service = GameServices::createService(static::$app_id);

            $url = $service->getLoginUrl($slg_id, $server_id, true);

            $name = empty($zing_user['fullname']) ? $zing_user['username'] : $zing_user['fullname'];

            UserHelper::logUserGame($slg_id, static::$app_id, static::$partner_id, $server_id);

            return view('game.onepiece.zing.play', compact('recent_server_id', 'servers', 'url', 'name'));
        }

        return redirect()->route('onepiece-zing.index');
    }

    public function getAuth(Request $request)
    {
        $this->logout($request);

        if ($request->has('sign_user') && $request->has('username') && $request->has('session_id') && $request->has('signed_request')) {
            $signed_request = $request->get('signed_request');

            try {
                $zm_Me = new ZME_Me(static::$config);
                $access_token = $zm_Me->getAccessTokenFromSignedRequest($signed_request);
                $me = $zm_Me->getInfo($access_token, 'id,username,displayname,gender,dob,tinyurl');
            } catch (\Exception $e) {
                return redirect()->route('onepiece-zing.index');
            }

            if (empty($me)) {
                return redirect()->route('onepiece-zing.index');
            }

            $zing_user = [];
            $zing_user['id'] = $me['id'];
            $zing_user['username'] = $me['username'];
            $zing_user['fullname'] = $me['displayname'];

            if ($me['gender'] == 0) {
                $zing_user['sex'] = 1;
            } elseif ($me['gender'] == 1) {
                $zing_user['sex'] = 0;
            }

            if (!empty($me['dob'])) {
                $zing_user['birthday'] = date('Y-m-d', $me['dob']);
            }

            if (!empty($me['tinyurl'])) {
                $zing_user['avatar'] = $me['tinyurl'];
            }

            $slg_user = OpenUser::firstOrCreate('zing', $zing_user);

            if (!isset($slg_user->id)) {
                return redirect()->route('onepiece-zing.index');
            }

            if ($slg_user->active == 0)
            {
                $request->session()->put('alert', 'Tài khoản SLG của bạn đang bị khóa.');
                return redirect()->route('onepiece-zing.index');
            }

            Auth::loginUsingId($slg_user->id);

            $zing_user['slg_id'] = $slg_user->id;

            $request->session()->put('zing_user', $zing_user);

            $recent_server_id = UtilHelper::gamePlaynow(static::$app_id, $slg_user->id, 0, static::$partner_id);

            $src = '';

            if ($request->has('utm_source')) {
                $src .= '&utm_source=' . $request->get('utm_source');
            }

            if ($request->has('utm_medium')) {
                $src .= '&utm_medium=' . $request->get('utm_medium');
            }

            if ($request->has('utm_content')) {
                $src .= '&utm_content=' . $request->get('utm_content');
            }

            if ($request->has('utm_term')) {
                $src .= '&utm_term=' . $request->get('utm_term');
            }

            if ($request->has('utm_campaign')) {
                $src .= '&utm_campaign=' . $request->get('utm_campaign');
            }

            if ($request->has('_srvid')) {
                return redirect()->to(route('onepiece-zing.play') . '?_srvid=' . $request->get('_srvid'));
            } elseif ($request->has('_src')) {
                $src .= '&_src=' . $request->get('_src');

                if ($request->get('_src') == 'm' && $request->has('t')) {
                    $src .= '&t=' . $request->get('t');
                }
            }

            return "<script>window.top.location.href = '" . static::$zing_url . '?_srvid=' . $recent_server_id . $src . "'</script>";
        }

        return redirect()->route('onepiece-zing.index');
    }

    protected function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('zing_user');
        $request->session()->forget('src');
        $request->session()->forget('alert');
    }

    public function getLogout(Request $request)
    {
        $this->logout($request);

        return redirect()->route('onepiece-zing.index');
    }

    public function getTop($server_id = 1, $type = 1)
    {
        $servers = GameServices::getServerList(static::$app_id, static::$partner_id);

        $server_id = intval($server_id) == 0 ? 1 : $server_id;

        $type = intval($type);

        if (!in_array($type, [1,2]))
        {
            $type = 1;
        }

        $service = GameServices::createService(static::$app_id);

        $tops = json_decode($service->getTopPlayers($server_id, $type, true), true);

        $zing_url = static::$zing_url;

        return view('game.onepiece.zing.top', compact('servers', 'type', 'server_id', 'tops', 'zing_url'));

    }

    public function postBilling(Request $request)
    {
        if (!$request->session()->has('zing_user')) {
            return -1;
        }

        if ($request->has('amount') && $request->has('server_id')) {
            $zing_user = $request->session()->get('zing_user');
            $server_id = $request->get('server_id');
            $amount = $request->get('amount');

            $data = new ZC2_BillingData();
            $data->uid = $zing_user['id']; //-- User Zing ID
            $data->billNo = time() . '_' . $server_id;
            $data->itemIDs = $server_id;
            $data->itemNames = 'Mua ' . $amount . ' vàng';
            $data->itemQuantities = '1';
            $data->itemPrices = $amount;
            $data->amount = $amount;
            $data->localUnixTimeStampInSecs = strval(time());

            $encodedData = ZCypher2Lib::encodeDataForBilling(static::$zing_credits_key1, $data);

            return static::$zing_billing_url . 'requestform?appID=' . static::$app_name . '&data=' . $encodedData;
        } else {
            return -2;
        }
    }

    public function getPayment(Request $request)
    {
        if (!$request->session()->has('zing_user')) {
            $request->session()->put('alert', 'Vui lòng đăng nhập!');
            return redirect()->route('onepiece-zing.index');
        }

        $zing_user = $request->session()->get('zing_user');

        $msg = 'Lỗi: Không lấy được thông tin Ví Zing Me, vui lòng thử lại hoặc liên hệ với chúng tôi.';

        try {
            $balance_instance = new ZC2_BalanceData();

            $balance_instance->uid = $zing_user['id']; //- User Zing ID

            $data = ZCypher2Lib::encodeDataForBalance(static::$zing_credits_key1, $balance_instance);

            if (!empty($data)) {
                $url = static::$zing_billing_url . 'balance?appID=' . static::$app_name . '&data=' . urlencode($data);

                $response = CommonHelper::cURLWithRetry('get', $url, 3);

                if ($response !== false) {
                    $balance = $response;
                }
            }

        } catch (\Exception $e) {
            $request->session()->put('alert', $msg);

            Log::debug('ZingCredits Exception: ' . $e->getMessage());

            return redirect()->route('onepiece-zing.index');
        }

        if (!isset($balance)) {
            $request->session()->put('alert', $msg);
            return redirect()->route('onepiece-zing.index');
        }

        $username = $zing_user['username'];

        $servers = GameServices::getServerList(static::$app_id, static::$partner_id);

        return view('game.onepiece.zing.payment', compact('username', 'servers', 'balance'));
    }

    public function getPaymentCallback(Request $request)
    {
        if ($request->has('data')) {
            $callback_instance = new ZC2_CallbackResultData();

            $callback_result = ZCypher2Lib::decodeDataForCallbackResult(static::$zing_credits_key2, $request->get('data'), $callback_instance);

            $data = (array)$callback_instance;

            if ($callback_result === 0) {

                $slg_user = User::findUserByProviderId($data['uid'], ['id']);

                if (!isset($slg_user->id)) {
                    Log::debug('ZingCredits Callback Error: SLG User Fetching');
                    goto end;
                }

                $server_id = $data['itemIDs'];
                $order_info = $data['itemNames'];
                $coin = $data['amount'];
                $order_id = $slg_user->id . time();

                $service = GameServices::createService(static::$app_id);

                $charge = $service->chargeByZing($slg_user->id, $server_id, $order_id, $coin, $order_info);

                if ($charge == 'success') {
                    return '1000:msg';
                }
            } else {
                Log::debug("ZingCredits Callback Error: Data Parsing value = $callback_result");
            }
        }

        end:
        return '-1000:msg';
    }
}