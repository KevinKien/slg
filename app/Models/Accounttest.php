<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class Accounttest extends Model {

    protected $table = 'testid';

    //protected $table2 = 'log_charge_coin_telco';


    public static function getAccount() {

        $Accountid = DB::table('testid')
            ->leftJoin('merchant_app', 'testid.game', '=', 'merchant_app.id')
            ->leftJoin('users', function ($join) {
                $join->on('testid.test_id', '=', 'users.id')->where('testid.type', '=', 'slg')
                    ->orOn('testid.test_id', '=', 'users.fid')->where('testid.type', '=', 'fpay');
            })
            ->select('testid.id','testid.test_id', 'merchant_app.name','users.name As username')
            ->paginate(10);
//        print_r($Accountid);die;
        return $Accountid;
    }

    public static function searchAccount($game){
        $Accountsearch = DB::table('testid')
            ->leftJoin('merchant_app', 'testid.game', '=', 'merchant_app.id')
            ->leftJoin('users','testid.test_id', '=','users.id', 'or', 'testid.test_id', '=', 'users.fid')
            ->select('testid.id','testid.test_id', 'merchant_app.name','users.name As username')
            ->where('testid.game',$game)
            ->paginate(10);
        return $Accountsearch;

    }

    public static function deleteAccid($id) {
        $query = DB::table('testid')
            ->where('id', $id)
            ->delete();

        return 200;
    }

    public static function insertAccid($testid, $game,$type) {
        $time = time();
        $query = DB::table('testid')
            ->insert(
                array('test_id' => $testid,
                    'game' => $game,
                    'created_at' => date('Y-m-d H:i:s',$time),
                    'type' => $type
                ));
        return 200;
    }

}

?>
