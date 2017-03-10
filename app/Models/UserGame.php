<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserGame extends Model
{
    protected $table = 'user_game';
    public $timestamps = true;
    protected $fillable = ['uid', 'app_id'];

    public static function ListGameServer($user_id,$game_id){
        $user_game_server = DB::table('user_game')->select('user_game.*','user_game_server.*')
            ->leftjoin('user_game_server','user_game_server.ugid','=','user_game.id')
            ->where('user_game.uid',$user_id)
            ->where('user_game.app_id',$game_id)
            ->where('user_game.partner_id',0)
            ->whereNotNull('user_game_server.server_id')
            ->get();
        return $user_game_server;
    }
}
