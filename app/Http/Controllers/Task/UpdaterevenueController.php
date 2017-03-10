<?php

namespace App\Http\Controllers\Task;

use \App;
use \SESSION;
use Validator;
use App\Models\MerchantApp;
use App\Models\LogCoinTransfer;
use App\Helpers\RedisKeyHelper;
use App\Helpers\Games\GameServices;
use App\Models\Merchant_app_cp;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DateTime,
    DateInterval,
    DatePeriod;

class UpdaterevenueController extends Controller {

    public function get_index() {
        dd('task revenue');
    }

    public function getListcpid() {
        $rs = Merchant_app_cp::AllCp();
        dd($rs);
    }

    public function getListkey() {
        $user = Auth::user();
        $game_name = Input::get('game');
        $game = MerchantApp::where('slug', $game_name)->firstOrFail();
        $service = GameServices::createService($game->id);
        $cpid = Merchant_app_cp::getCpidUser($user->provider, $game->id);
        dd($cpid);
    }

    public function getLogredisbykey() {
        $key = Input::get('key');
        if (empty($key)) {
            $key = 'Application_amount_REVENUE_300000125_16_03_22';
        }
        $rs = \App\Models\LogRedis::getLogByKey($key);
        $arr = array();
        $sum = 0;
        foreach ($rs as $key) {
            $tmp = json_decode($key->value);
            $arr[] = $tmp;
            $sum += $tmp->amount;
        }
        dd($arr);
    }

    public function getWeekrevenuegames() {
        $today = date('Y-m-d');
        $begin = new DateTime($today);
        $end = new DateTime($today);
        $begin->modify('-7 day');
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $range = new DatePeriod($begin, $interval, $end);

        $cps = Merchant_app_cp::AllCp();
        $game = Input::has('app_id') ? Input::get('app_id') : '17054245';
        $date = Input::has('date') ? Input::get('date') : date('Y-m-d');
        $rs = LogCoinTransfer::getGameDailyRevenue($game, $date);
        dd($rs);
        $partner = Input::has('partner') ? Input::get('partner') : 'Fpay';
        $partner = ucfirst($partner);

        $cpid = Input::has('cpid') ? Input::get('cpid') : '300000125';
        $key = RedisKeyHelper::getRevenueKey($cpid, $date);
        dd($key);
    }

}
