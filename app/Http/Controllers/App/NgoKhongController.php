<?php

namespace App\Http\Controllers\App;

use \App;
use \SESSION;
use Validator;
use App\Models\User;
use App\Models\MerchantApp;
use App\Helpers\Games\GameServices;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class NgoKhongController extends Controller {

    public function __construct() {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    public function get_index(Request $request) {
        dd($request);
        return;
    }

    public function getSlg(Request $request) {
        if (!Auth::check()) {
            dd('login error');
            return;
        }
        $user = Auth::user();
        $server_id = Input::get('server');
        $game = MerchantApp::where('slug', trim('manga-dai-chien'))->first();
        $service = GameServices::createService($game->id);
        $get_game_url = $service->getLoginUrl($user->id, $server_id);
        $servers = GameServices::getServerList($game->id);

        return view('game.manga.gameserver', ['url' => $get_game_url, 'servers' => $servers,'server_id' => $server_id]);
    }

}
