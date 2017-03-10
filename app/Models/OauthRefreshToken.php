<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class OauthRefreshToken extends Model
{
    protected $table = 'oauth_refresh_tokens';
    
    public function CheckAvaiableRefreshToken($refresh_token = 0){
        
        $rt_av = DB::table('oauth_refresh_tokens')
                ->where('id',$refresh_token)
                ->where('expire_time','>=',  strtotime("now"))
                ->count();
        if($rt_av > 0){
            return TRUE;
        } 
        return FALSE;
    }

}

