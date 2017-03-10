<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class HistoryEvent extends Model
{
    protected $table = 'history_events';
    public $timestamps = true;
    protected $guarded = [];


    public static function CheckLog($id_user,$game_id,$server_id,$gift_code_type){
        $history = DB::table('history_events')->where('user_id',$id_user)->where('game_id',$game_id)->where('server_id',$server_id)->where('gift_code_type',$gift_code_type)->first();
        return $history;
    }
}
