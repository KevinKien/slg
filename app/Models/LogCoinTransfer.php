<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon,
    DB;

class LogCoinTransfer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_coin_transfer';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function game() {
        return $this->belongsTo('App\Models\MerchantApp', 'app_id', 'id');
    }

    public static function getLog($uid, $app_id = '') {
        $q = DB::table('log_coin_transfer')->where('user_id', $uid)
                ->whereBetween('request_time', [Carbon::now()->subMonth(3), Carbon::now()])
                ->join('merchant_app', function ($join) {
                    $join->on('log_coin_transfer.app_id', '=', 'merchant_app.id');
                })
                ->leftJoin('merchant_app_config', function ($join) {
            $join->on('log_coin_transfer.server_id', '=', 'merchant_app_config.serverid');
            $join->on('log_coin_transfer.app_id', '=', 'merchant_app_config.appid');
        });

        if (!empty($app_id)) {
            $q->where('app_id', $app_id);
        }
       
        return $q->select('log_coin_transfer.*', 'merchant_app_config.servername', 'merchant_app.name')
                        ->orderBy('request_time', 'desc')->paginate(20);
        
    }

    public static function getTransfercoinbydate($dateform) {
        $data = DB::table('log_coin_transfer')
                ->join('users', 'users.id', '=', 'log_coin_transfer.user_id')
                ->join('cash_info', 'cash_info.uid', '=', 'log_coin_transfer.user_id')
                ->select(DB::raw('count(coin) as Numbercoin'), 'log_coin_transfer.user_id', 'log_coin_transfer.coin', 'users.name', 'cash_info.coins', 'log_coin_transfer.request_time')
                ->where('log_coin_transfer.response', 'like', '%success%')
                ->where('log_coin_transfer.request_time', 'like', $dateform)
                ->groupBy('log_coin_transfer.coin')
                ->groupBy('users.name')
                ->get();
//        print_r($data);

        return $data;
    }

    public static function getGameDailyRevenue($game, $date = 0) {
        if (empty($game)) {
            return;
        }
        if (empty($date)) {
            $date = date("Y-m-d H:i:s");
        }
        $rs = static::where('app_id', $game)
                ->whereDay('request_time', '=', date('d', strtotime($date)))
                ->whereMonth('request_time', '=', date('m', strtotime($date)))
                ->whereYear('request_time', '=', date('Y', strtotime($date)))
                ->where('status', 1)
                ->sum('coin');

        return ['date' => $date, 'revenue' => $rs];
    }

    public static function getLogFpay(){
        $fromdate = date('Y-m-d', $_GET['datefrom']).' 00:00:00';
        $todate = date('Y-m-d', $_GET['dateto']).' 23:59:59';
        $cpid = $_GET['cpid'];
        $result = DB::table('log_coin_transfer')
            ->select(DB::raw('coin as amount'),DB::raw('user_id as uid'), DB::raw('request_time as time'),'trans_id','response')
            ->where('status', '1')
            ->where('request_time','>=', $fromdate)
            ->where('request_time','<=', $todate)
            ->where('app_id',$cpid)
            ->orderBy('request_time', 'DESC')
            ->get();
        return $result;
    }

    public static function getGameWeekendRevenue() {
        
    }

}
