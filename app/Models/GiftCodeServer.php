<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class GiftCodeServer extends Model
{
    protected $table = 'gift_game_servers';
    public $timestamps = true;
    protected $guarded = [];

    public static function TakeCode($event_id,$game_id,$server_id,$gift_code_type,$status){
        $takecode = DB::table('gift_game_servers')
            ->where('event_id',$event_id)
            ->where('game_id',$game_id)
            ->where('server_id',$server_id)
            ->where('gift_code_type',$gift_code_type)
            ->where('status',$status)
            ->get();
        return $takecode;
    }
}
