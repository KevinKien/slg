<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Log_buy_item_soha extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_buy_item_soha';
    public $timestamps = false;

    public static function getLogbuyitemsoha($dateform,$cpid,$dateto) {
//        if(!isset($_GET['bydate'])){
//            $dateform = strtotime(date('Y-m-d', time()).'%');
//        }else{
//            $dateform = strtotime(date('Y-m-d', $_GET['bydate']).'%');
//        }
//        print_r($dateform);die;
        $data = DB::table('log_buy_item_soha')
            ->select('userid', 'request_time', 'item_price','server_id')
            ->where('cpid', $cpid)
            ->where('request_time','>=', $dateform)
            ->where('request_time','<=', $dateto)
            ->get();
        return $data;
    }

    public static function getLogbuyitemsohabyuserid($dateform,$cpid,$userid,$dateto) {
        $data = DB::table('log_buy_item_soha')
            ->select('userid', 'request_time', 'item_price','server_id')
            ->where('cpid', $cpid)
            ->where('request_time','>=', $dateform)
            ->where('request_time','<=', $dateto)
            ->where('userid', $userid)
            ->get();
        return $data;
    }
}
