<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class UserGiftcode extends Model {

    protected $table = 'giftcodes';



    public static function getgitfcode($uid,$appid,$type) {
        $data = array();
        
        $data = DB::table('giftcodes')                           
                ->where('uid', $uid)
                ->where('appid', $appid)
                ->where('type', $type)                
                ->get();
        
        if(empty($data[0]->giftcodes)){
            
            $data = DB::table('giftcodes')                                           
                ->where('appid', $appid)
                ->where('type', $type)                
                ->where('status', 1)    
                ->offset(0)
                ->take(1)
                ->get();
            
            if(!empty($data[0]->giftcodes)){
                $query = DB::table('giftcodes')
                ->where('id', $data[0]->id)
                ->update(
                        array(                            
                            'status' => 2,
                            'uid' => $uid
                ));
            }else{
                return 2;
            }
        }
                        
        return $data;
    }

}
