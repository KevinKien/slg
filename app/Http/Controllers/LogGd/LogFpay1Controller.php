<?php
namespace App\Http\Controllers\LogGd;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache;
use App\Models\MerchantApp;
use App\Models\Merchant_app_cp;
use ArrayPaginator,DB;
class LogFpay1Controller extends Controller
{
    public function index(Request $request, $page = 1)
    {
         if(!isset($_GET['cpid'])){
            $cpid=300000125;
        }  else {
           $cpid=$_GET['cpid'] ;
        }
//        $list_game=  Merchant_app_cp::list_game();
//        $list_game1 = DB::table('merchant_app_cp')->where('cpid', '=', $cpid)->where('partner_id', '=', 100000010)
//            ->get();
//        $list_game2 = Merchant_app_cp::where('partner_id', '=', 100000010)
//            ->lists('cp_name', 'cpid');
        $list_game2 = Merchant_app_cp::getMerchantbyparnerId();
        $result_total=MerchantApp::get_log_payment_fpay1($request);
//        dd($result_total);
        $data = MerchantApp::get_log_payment_fpay1($request);
        $account = MerchantApp::get_Account_test_fpay();
//       if ($request->has('dateform') && $request->has('dateto'))
//           $dateform = $request->input('dateform');
//         $dateto = $request->input('dateto');
//        print_r($request->all());die;

        If(!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['cpid']) ){
            $url='';
        }else if(isset($_GET['dateform']) && isset($_GET['dateto']) && isset($_GET['cpid']) && isset($_GET['uid']) ){

            $url='?dateform='.$_GET['dateform'].'&dateto='.$_GET['dateto'].'&cpid='.$_GET['cpid'].'&uid='.$_GET['uid'].'';
        }else{
            $url='?dateform='.$_GET['dateform'].'&dateto='.$_GET['dateto'].'&cpid='.$_GET['cpid'].'';
        }
        $url_pattern = route('log-fpay1') . '/(:num)'.$url.'';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $result = $paginator->getResult();

        $paginator_html = $paginator->render();
        return view('/logGD/list_log_fpay1',  compact('result_total','result', 'paginator_html','page','list_game2', 'cpid','account'));
    }

    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $dateform=strtotime($dulieu_tu_input["date-from"]);
        $dateto=strtotime($dulieu_tu_input["date-to"]);
        $game=$dulieu_tu_input['game'];
        $uid=$dulieu_tu_input['uid'];
        if(empty($uid)){
            return redirect('/log_fpay1?dateform='.$dateform.'&dateto='.$dateto.'&cpid='.$game.'');
        }else{
            return redirect('/log_fpay1?dateform='.$dateform.'&dateto='.$dateto.'&cpid='.$game.'&uid='.$uid.'');
        }
    }
}