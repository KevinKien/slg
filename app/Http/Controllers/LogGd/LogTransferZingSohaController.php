<?php

namespace App\Http\Controllers\LogGd;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use ArrayPaginator,DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Http\Requests;
use App\Models\Log_buy_item_soha;
use App\Models\Log_buy_item_zing;
use App\Http\Controllers\Controller;

class LogTransferZingSohaController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function index(Request $request,$page = 1) {
        $listpart = DB::table('merchant_app_cp')
            ->select('describe')
            ->where('describe','zing')
            ->orwhere('describe','soha')
            ->groupBy('describe')
            ->get();
        $listgame = array();
        $partner1 = '';
        If(!isset($_GET['partner']) ){
            $partner1 = 'Soha';
            $listgame = DB::table('merchant_app_cp')->where('describe', '=', $partner1)->get();
        }else {
            $partner1 = $_GET['partner'];
            $listgame = DB::table('merchant_app_cp')->where('describe', '=', $partner1)->get();
        }
        If(!isset($_GET['cpid']) ){
            $part = '300000186';
        }else {
            $part = $_GET['cpid'];
        }
        $datefrom1 ='';
        $dateto1 ='';
        if(!isset($_GET['datefrom'])&&!isset($_GET['dateto'])){
            $datefrom1 = time();
            $dateto1 = time();
        }else{
            $datefrom1 = $_GET['datefrom'];
            $dateto1 = $_GET['dateto'];
        }
        $datefrom='';
        $dateto='';
        if($partner1 == 'Soha'){
            $datefrom = date('Y-m-d',$datefrom1).' 00:00:00';
            $dateto= date('Y-m-d',$dateto1).' 23:59:59';
            if(!isset($_GET['uid'])) {
                $data = Log_buy_item_soha::getLogbuyitemsoha($datefrom,$part,$dateto);
            }else{
                $data = Log_buy_item_soha::getLogbuyitemsohabyuserid($datefrom,$part,$_GET['uid'],$dateto);
            }
        }else{
            $datefrom = date('Y-m-d',$datefrom1).'%';
            $dateto= date('Y-m-d',$dateto1).'%';
            if(!isset($_GET['uid'])) {
                $data = Log_buy_item_zing::getLogbuyitemzings($datefrom,$part,$dateto);
            }else{
                $data = Log_buy_item_zing::getLogbuyitemzingbyuserid($datefrom,$part,$_GET['uid'],$dateto);
            }
        }

        If(!isset($_GET['datefrom']) && !isset($_GET['cpid']) ){
            //print_r(1);die;
            $url='';
        }else if(isset($_GET['datefrom']) && isset($_GET['cpid']) && isset($_GET['uid']) ){

            $url='?datefrom='.$_GET['datefrom'].'&dateto='.$_GET['dateto'].'&cpid='.$_GET['cpid'].'&uid='.$_GET['uid'].'&partner='.$partner1;
        }else{
            $url='?datefrom='.$_GET['datefrom'].'&dateto='.$_GET['dateto'].'&cpid='.$_GET['cpid'].'&partner='.$partner1;
        }
        $url_pattern = route('log-buy-item') . '/(:num)'.$url.'';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $results = $paginator->getResult();

        $paginator_html = $paginator->render();

        return view('/logGD/list_log_buy_item', compact('listpart','listgame','partner1','part','results','paginator_html','page'));
    }

    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $datefrom=strtotime($dulieu_tu_input["date-from"]);
        $dateto=strtotime($dulieu_tu_input["date-to"]);
        $cpid=$dulieu_tu_input["new_select"];
        $partner = $dulieu_tu_input["partner"];
        $uid=$dulieu_tu_input['uid'];
        if(empty($uid)){
            return redirect('/log_buy_item?datefrom='.$datefrom.'&dateto='.$dateto.'&cpid='.$cpid.'&partner='.$partner);
        }else{
            return redirect('/log_buy_item?datefrom='.$datefrom.'&dateto='.$dateto.'&cpid='.$cpid.'&partner='.$partner.'&uid='.$uid.'');
        }

    }

    public function store1()
    {
        $data_describe = $_REQUEST['data_describe'];
        $return = DB::table('merchant_app_cp')->where('describe', '=', $data_describe)->get();
        return json_encode($return);
//        return $return;
    }

}