<?php

namespace App\Http\Controllers\LogGd;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\LogChargeTelco;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LogCoinUserController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function index() {
        //return view('compen.index');
        $dateform = '';
        $time = time();
        if(!isset($_GET['bydate'])){
            $dateform = date('Y-m-d', $time).'%';
        }else{
            $dateform = date('Y-m-d', $_GET['bydate']).'%';
        }
        $logcoin = LogChargeTelco::getLogGDTelcobydate($dateform);
        $logtype = LogChargeTelco::getLogGDTelcobytype($dateform);
        return view('/logGD/list_log_coin', compact('logcoin','logtype'));
    }

    public function getSearch(Request $request){
        $game = "";
        $game1 = "";
        if(empty($request)){
            $game1 = 1001;
        }else{
            $game = trim($request->get('game1'));
            $game1 = trim($request->get('game1'));

        }
        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        $account = Accounttest::searchAccount($game);
        $message = "";
        $listgame = DB::table('merchant_app')
            ->lists('merchant_app.name','merchant_app.id');
        return view('user.accounttest', compact('account','listgame','message','game1','page'));
    }

    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $bydate=strtotime($dulieu_tu_input["bydate"]);

        return redirect('/log_coin?bydate='.$bydate.'');
    }
}