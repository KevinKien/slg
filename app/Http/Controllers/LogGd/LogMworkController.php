<?php
namespace App\Http\Controllers\LogGd;

use Illuminate\Http\Request;
use App\Models\Dau_log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\Models\MerchantApp;
use ArrayPaginator;

class logMworkController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $page = 1) {
         if(!isset($_GET['cpid'])){
            $cpid=300000001;
        }  else {
           $cpid=$_GET['cpid'] ;
        }
        //$list_game=  Merchant_app_cp::list_game();
        $list_game1 = DB::table('merchant_app_cp')->where('cpid', '=', $cpid)->where('partner_id', '=', 100000001)
            ->get();
        $list_game2 = DB::table('merchant_app_cp')->where('cpid', '!=', $cpid)->where('partner_id', '=', 100000001)
            ->get();
        $result_total = MerchantApp::get_log_mwork();
        $data=MerchantApp::get_log_mwork();
        If(!isset($_GET['dateform'])&&!isset($_GET['dateto'])&&!isset($_GET['cpid'])){
            $url='';
        }else{
            $url='?dateform='.$_GET['dateform'].'&dateto='.$_GET['dateto'].'&cpid='.$_GET['cpid'].'';
        }
        $url_pattern = route('log-mwork') . '/(:num)'.$url.'';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $result = $paginator->getResult();

        $paginator_html = $paginator->render();
        return view('/logGD/list_log_mwork', compact('result','list_game1','list_game2','result_total','paginator_html','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) 
    {
        $dulieu_tu_input = $request->all();
        $dateform=strtotime($dulieu_tu_input["date-from"]);
        $dateto=strtotime($dulieu_tu_input["date-to"]);
       
        $game=$dulieu_tu_input['game'];
       // $appid=$dulieu_tu_input['type'];
        return redirect('/log_mwork?dateform='.$dateform.'&dateto='.$dateto.'&cpid='.$game.'');
        
    }

}
