<?php
namespace App\Http\Controllers\LogGd;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache;
use App\Models\MerchantApp;
use ArrayPaginator;
class LogFpayController extends Controller
{
    public function index(Request $request, $page = 1)
    {
        $result_total=MerchantApp::get_log_payment_fpay();
//        dd($result_total);
        $data = MerchantApp::get_log_payment_fpay();
//       if ($request->has('dateform') && $request->has('dateto'))
//           $dateform = $request->input('dateform');
//         $dateto = $request->input('dateto');
        If(!isset($_GET['dateform'])&&!isset($_GET['dateto'])&&!isset($_GET['appid'])&&!isset($_GET['type'])){
            $url='';
        }else{
            $url='?dateform='.$_GET['dateform'].'&dateto='.$_GET['dateto'].'&appid='.$_GET['appid'].'&type='.$_GET['type'].'';
        }
        $url_pattern = route('log-fpay') . '/(:num)'.$url.'';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $result = $paginator->getResult();

        $paginator_html = $paginator->render();

        return view('/logGD/list_log_fpay',  compact('result_total','result', 'paginator_html','page'));
    }

    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $dateform=strtotime($dulieu_tu_input["date-from"]);
        $dateto=strtotime($dulieu_tu_input["date-to"]);
        $game=$dulieu_tu_input['game'];
        $type=$dulieu_tu_input['type'];
        return redirect('/log_fpay?dateform='.$dateform.'&dateto='.$dateto.'&appid='.$game.'&type='.$type.'');
        
    }
}