<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OauthAccessToken extends Model
{
    protected $table = 'oauth_access_tokens';

    public function getuinfoaccesstoken($access_token, $client_id)
    {
        $user_info = DB::table('oauth_access_tokens')
            ->where('oauth_access_tokens.id', $access_token)
            ->join('oauth_sessions', 'oauth_sessions.id', '=', 'oauth_access_tokens.session_id')
            ->where('oauth_sessions.client_id', $client_id)
            ->join('users', 'users.id', '=', 'oauth_sessions.owner_id')
            ->select('users.*')->first();
        return $user_info;
    }

    public function CheckAvaiableAccessToken($access_token = 0)
    {

        $rt_av = DB::table('oauth_access_tokens')
            ->where('oauth_access_tokens.id', $access_token)
            ->where('oauth_access_tokens.expire_time', '>=', time())
            ->join('oauth_sessions', 'oauth_sessions.id', '=', 'oauth_access_tokens.session_id')
            ->select('oauth_sessions.owner_id')
            ->first();
        if ($rt_av) {
            return $rt_av->owner_id;
        }
        return FALSE;
    }

}

