<?php

namespace App\Http\Controllers\LogGd;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use ArrayPaginator,DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\LogChargeTelco;
use App\Models\LogCoinTransfer;
use Illuminate\Pagination\AbstractPaginator;
use App\Models\CoinLog;
use App\Models\CashInfo;
use App\Models\LogRedis;
use App\Models\MerchantApp;
use App\Models\Accounttest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LogDetailTransferController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function index(Request $request,$page = 1) {
        //return view('compen.index');
        $data = MerchantApp::getKeyDbLogRedisbydate();
        
        $listapp = DB::table('merchant_app')
            ->get();
        $account = MerchantApp::get_Account_test_fpay();
        $listpart = array();
        $game1 = '';
        If(!isset($_GET['game']) ){
            $game1 = '1001';
            $listpart = DB::table('merchant_app_cp')->where('app_id', '=', $game1)->get();
        }else {
            $game1 = $_GET['game'];
            $listpart = DB::table('merchant_app_cp')->where('app_id', '=', $game1)->get();
        }
        If(!isset($_GET['cpid']) ){
            $part = '300000121';
        }else {
            $part = $_GET['cpid'];
        }

        If(!isset($_GET['datefrom']) && !isset($_GET['cpid'])&& !isset($_GET['dateto']) ){
            //print_r(1);die;
            $url='';
        }else {
//            print_r(2);die;
            $url='?datefrom='.$_GET['datefrom'].'&dateto='.$_GET['dateto'].'&cpid='.$_GET['cpid'].'&game='.$_GET['game'].'';
        }
        $url_pattern = route('log-detail-transfer') . '/(:num)'.$url.'';
        
        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $results = $paginator->getResult();

        $paginator_html = $paginator->render();

        return view('/logGD/list_log_detail_transfer', compact('data','results','listapp','paginator_html','account','page','game1','listpart','part'));
    }

    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $fromdate=strtotime($dulieu_tu_input["date-from"]);
        $todate=strtotime($dulieu_tu_input["date-to"]);
        $cpid=$dulieu_tu_input["new_select"];
        $game = $dulieu_tu_input["game1"];
        return redirect('/log_detail_transfer?datefrom='.$fromdate.'&dateto='.$todate.'&cpid='.$cpid.'&game='.$game);
    }

    public function store1()
    {
        $data_ids = $_REQUEST['data_ids'];
        $return = DB::table('merchant_app_cp')->where('app_id', '=', $data_ids)->get();
        return json_encode($return);
//        return $return;
    }
}