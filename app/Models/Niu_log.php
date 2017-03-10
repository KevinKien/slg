<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core_partner_info;
use App\Models\Merchant_app_cp;
use Illuminate\Support\Facades\Redis;
use DB;

class Niu_log extends Model {

    //
    protected $table = 'niu_log';

    public function show_week($cpid) {
        $log = DB::table('niu_log')
                ->where('dau_log.cpid', $cpid)
                ->where('created_at', '>', date('Y-m-d 0:0:0', strtotime("-7 day")))
                ->join('merchant_app_cp', 'merchant_app_cp.cpid', '=', 'dau_log.cpid')
                ->select('dau_log.*','merchant_app_cp.cp_name')
                        ->take(8)->get();
        return $log;
    }
    public static function show_date($cpid,$datefrom,$dateto) {
        $from = substr($datefrom, 8, 2).substr($datefrom, 3, 2).substr($datefrom, 0, 2);
        $to = substr($dateto, 8, 2).substr($dateto, 3, 2).substr($dateto, 0, 2);
        $log = DB::table('niu_log')
                ->where('niu_log.cpid','=', $cpid)
                ->where('name', '>=', $from)
                ->where('name', '<=', $to)
                ->select("value")
                ->orderBy('name', 'asc')
                ->get();
        return $log;
    }

    public static function list_partner($partner_id,$update = 0) {
        if($partner_id['0']){
            return Core_partner_info::wherein('partner_id', $partner_id)->get();
        }else{
            $list_partner = Redis::get(PARTNER_LIST_KEY);
            if($update == 1|empty($list_partner)){
                $list_partner = Core_partner_info::get();
                Redis::set(PARTNER_LIST_KEY, $list_partner);
                return $list_partner;
            }else{
                return $list_partner;
            }
        }
    }
    public static function list_partner_app($app_id){
        $result =array();
        $querry = Merchant_app_cp::where('app_id',$app_id)->groupBy('partner_id')->select('partner_id')->get();
        foreach ($querry as $value) {
            $result[] = $value->partner_id;
        }
        return $result;
    }
    public static function list_cp_app($app_id,$partner_id){
        if($partner_id != 0){
            $result = Merchant_app_cp::where('app_id',$app_id)->where('partner_id',$partner_id)->groupBy('cpid')->select('cpid','cp_name','app_id')->get();
        }else{
            $result = Merchant_app_cp::where('app_id',$app_id)->groupBy('cpid')->select('cpid','cp_name','app_id')->get();
        }
        return $result;
    }

    public static function list_appid($id,$user_type) {
        Redis::set(APP_ID_UPDATE_KEY, 1);
        $appid_updated = Redis::get(APP_ID_UPDATE_KEY);
        $query_list = Redis::get(APP_ID_LIST_KEY);
        $list_appid_querry ='';
        if($user_type =='partner'){
            $list_appid_querry = DB::table('merchant_app_cp')
                    ->where('merchant_app_cp.partner_id', '=', $id)
                    ->groupBy('merchant_app_cp.app_id')
                    ->join('merchant_app', 'merchant_app_cp.app_id', '=', 'merchant_app.id')
                    ->select('merchant_app_cp.partner_id', 'merchant_app_cp.app_id', 'merchant_app.name')
                    ->get();
            
        }elseif ($user_type == 'deploy') {
            $list_appid_querry = DB::table('merchant_app_cp')
                    ->where('merchant_app_cp.app_id', '=', $id)
                    ->groupBy('merchant_app_cp.app_id')
                    ->join('merchant_app', 'merchant_app_cp.app_id', '=', 'merchant_app.id')
                    ->select('merchant_app_cp.partner_id', 'merchant_app_cp.app_id', 'merchant_app.name')
                    ->get();
            
        }elseif ($user_type == 'admin') {
//            $id ='1008';
            $list_appid_querry = DB::table('merchant_app_cp')
//                    ->where('merchant_app_cp.app_id', '=', $id)
                    ->groupBy('merchant_app_cp.app_id')
                    ->join('merchant_app', 'merchant_app_cp.app_id', '=', 'merchant_app.id')
                    ->select('merchant_app_cp.partner_id', 'merchant_app_cp.app_id', 'merchant_app.name')
                    ->get();
            
        }
        
        return $list_appid_querry;
    }

    public static function list_cpid($app_id, $partner_id, $user_type) {
        $app_id_cp = array();
        $parner_id_cp = array();
        foreach ($partner_id as $key => $partner_id) {
            $parner_id_cp[] = $partner_id->partner_id;
        }
        
        $list_cpid_querry ='';
        if($user_type =='admin'){
            $list_cpid_querry = DB::table('merchant_app_cp')
                    ->get();
        }elseif($user_type =='deploy'|$user_type =='partner'){
            $list_cpid_querry = Merchant_app_cp::
                    where('app_id',  $app_id['0'])
                    ->wherein('partner_id', $parner_id_cp)
                    ->get();
        }
        return $list_cpid_querry;
    }
    public static function get_cp_name($cpid){
        $querry = DB::table('merchant_app_cp')
                    ->where('cpid', '=', $cpid)
                    ->select('cp_name') 
                    ->get();
        return $querry[0]->cp_name;
    }

}
