<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon,
    DB;

class LogBuyItemZing extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_buy_item_zing';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    protected $fillable = ['userid', 'zing_order_info', 'cpid'];
    public $timestamps = false;

    public static function findLog($order_info, $status = 0) {

        if (empty($status)) {
            return static::where('order_id', $order_info)->firstOrFail();
        }

        if (!empty($order_info) && !empty($status)) {
            return static::where('order_id', $order_info)
                            ->where('status', $status)
                            ->firstOrFail();
        }
        return new static;
    }

    public static function logBuyItemStep1($arr) {

        if (empty($arr['order_id'])) {
            return;
        }
        $log = new LogBuyItemZing();
        $log->cpid = $arr['cpid'];
        $log->userid = $arr['userid'];
        $log->server_id = $arr['server_id'];
        $log->request_time = date("Y-m-d H:i:s");
        $log->channel_id = $arr['channel_id'];
        $log->item_price = $arr['item_price'];
        $log->order_id = $arr['order_id'];
        $log->status = $arr['status'];

        return $log->save();
    }

    public static function getItemByOrderInfo($order_info) {
        $log = self::findLog($order_info);
        return $log;
    }

    public static function logBuyItemStep2($order_info, $soha_order_info) {
        $log = self::findLog($order_info);
        $log->status = 2;
        $log->soha_order_info = $soha_order_info;
        return $log->save();
    }

    public static function getLogBuySohaTransid($order_info, $status) {
        if (!empty($order_info) && !empty($status)) {
            return static::where('zing_order_info', $order_info)
                            ->where('status', $status)
                            ->firstOrFail();
        }
        return new static;
    }
    
    public static function getGameDailyRevenue($game, $date = 0) {
        if (empty($game)) {
            return;
        }
        if (empty($date)) {
            $date = date("Y-m-d H:i:s");
        }
        $rs = static::where('cpid', $game)
                ->whereDay('request_time', '=', date('d', strtotime($date)))
                ->whereMonth('request_time', '=', date('m', strtotime($date)))
                ->whereYear('request_time', '=', date('Y', strtotime($date)))
                ->where('status', 3)
                ->sum('item_price');

        return ['date' => $date, 'revenue' => $rs];
    }

    public static function getLogZing(){
        $fromdate = date('Y-m-d', $_GET['datefrom']).' 00:00:00';
        $todate = date('Y-m-d', $_GET['dateto']).' 23:59:59';
        $cpid = $_GET['cpid'];
        $result = DB::table('log_buy_item_zing')
            ->select(DB::raw('item_price as amount'),DB::raw('userid as uid'), DB::raw('request_time as time'),'channel_id','cpid','status')
            ->where('request_time','>=', $fromdate)
            ->where('request_time','<=', $todate)
            ->where('cpid',$cpid)
            ->orderBy('request_time', 'DESC')
            ->get();

        return $result;
    }

}
