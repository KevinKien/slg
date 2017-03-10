<?php

namespace App\Http\Controllers\LogGd;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\LogCoinTransfer;
use App\Models\CoinLog;
use App\Models\CashInfo;
use App\Models\Accounttest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LogTransferCoinUserController extends Controller {

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
        $logcoin = LogCoinTransfer::getTransfercoinbydate($dateform);

        return view('/logGD/list_log_transfer_coin', compact('logcoin'));
    }

    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $bydate=strtotime($dulieu_tu_input["bydate"]);

        return redirect('/log_transfer_coin?bydate='.$bydate.'');
    }


}