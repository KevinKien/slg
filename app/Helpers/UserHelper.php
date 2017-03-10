<?php

namespace App\Helpers;

use App\Models\UserGame;
use App\Models\UserGameServer;

class UserHelper
{
    /**
     * @param $uid
     * @param $app_id
     * @param int $partner_id
     * @param int $server_id
     */
    public static function logUserGame($uid, $app_id, $partner_id = 0, $server_id = 0)
    {
        $user_game = UserGame::firstOrNew(['uid' => $uid, 'app_id' => $app_id, 'partner_id' => $partner_id]);
        $user_game->updated_at = date('Y-m-d H:i:s');
        $user_game->save();

        if ($server_id > 0) {
            $user_game_server = UserGameServer::firstOrNew(['ugid' => $user_game->id, 'server_id' => $server_id]);
            $user_game_server->updated_at = date('Y-m-d H:i:s');
            $user_game_server->save();
        }
    }
}