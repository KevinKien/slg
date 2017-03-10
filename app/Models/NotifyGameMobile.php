<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Oauth_clients;
use DB;
class NotifyGameMobile extends Model
{
    protected $table = 'notify_game_mobile';
     public $timestamps = false;
    public static function get_all_log_device_by_user($appid,$uid)
    {
        $log=DB::table('notify_game_mobile')
                ->where('merchant_app_id','=',$appid)
                ->where('uid','=',$uid)
                ->select('os_id','device_id')
                ->get();  
	return $log;
	}
		
    public static function get_all_log_device($appid)
    {
	$log=DB::table('notify_game_mobile')
                ->where('merchant_app_id','=',$appid)
                ->select('os_id','device_id')
                ->get();  
	return $log;
	}
    
    public static function save_device_token($uid,$os_id,$device_token,$app_id)
    {		
	$user_game_mobile=DB::table('notify_game_mobile')
                         ->where('os_id','=',$os_id)
                         ->where('merchant_app_id','=',$app_id)
                         ->where('device_id','=',$device_token)
                         //->select('os_id')
                         ->get();
                 foreach ($user_game_mobile as $value) {}
	if($user_game_mobile == null) {
            
            DB::table('notify_game_mobile')->insert(
                ['time_create' => time(),//Cập nhật thời gian và token
                  'device_id' => $device_token,			
                  'os_id' => $os_id,	
                  'uid' => $uid,			
                  'merchant_app_id' => $app_id,
                    ]
            );
	}
	else
	{
            DB::table('notify_game_mobile')
            ->where('notify_id', $value->notify_id)
            ->update(['time_create' => time(),//Cập nhật thời gian và token
                    'uid' => $uid,				
                    'device_id' => $device_token,]);
	}
			
            return 200;
	}
}

