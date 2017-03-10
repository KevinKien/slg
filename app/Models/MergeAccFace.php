<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB,
    App\User;

class MergeAccFace extends Model  {


    protected $table = 'log_merge_acc_face';
    public $timestamps = true;

    //ghi log bù coin
    public static function insertLog($idfrom, $idto, $admin, $detail, $time) {
        //ghi log bù coin        
        $query = DB::table('log_merge_acc_face')
                ->insert(
                array(
                    'idfrom' => $idfrom,
                    'idto' => $idto,
                    'useradmin' => $admin,
                    'detail' => $detail,
                    'status' => 1,
                    'created_at' => $time
        ));
        return 200;
    }
    public static function BackMergeAcc($id){
        
        $log_arr = MergeAccFace::where('id',$id)->first();
        
        if ($log_arr->status == 1) {
            $log = json_decode($log_arr->detail);
            $query1 = DB::table('users')
                    ->where('id', $log->toid)
                    ->update(
                    array(
                        'name' => $log->toname,
                        'email' => $log->toemail,
                        'dataext' => '',
            ));
            $query2 = DB::table('users')
                    ->where('id', $log->fromid)
                    ->update(
                    array(
                        'name' => $log->fromname,
                        'email' => $log->fromemail,
                        'dataext' => '',
                        
            ));
            
            $log_arr->status = 3;
            
            $log_arr->save();
            return 200;
        }else{
            return 0;
        }
//        print_r(json_decode($log->detail));die;
    }

}
