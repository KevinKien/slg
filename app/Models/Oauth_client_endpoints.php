<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Oauth_clients;
use DB;

class Oauth_client_endpoints extends Model
{
    protected $table = 'oauth_client_endpoints';

    public function list_game()
    {
        $log = DB::table('oauth_client_endpoints')
            ->join('oauth_clients', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')
            ->select('oauth_client_endpoints.*', 'oauth_clients.name', 'oauth_clients.secret')
            ->paginate(10);
        return $log;
    }

    public function list_game1($id)
    {
        $result = DB::table('oauth_client_endpoints')
            ->join('oauth_clients', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')
            ->select('oauth_client_endpoints.*', 'oauth_clients.name', 'oauth_clients.secret')
            ->where('oauth_client_endpoints.client_id', '=', $id);
        return $result;
    }
}

