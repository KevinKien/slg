<?php
namespace App\Http\Controllers\LogGd;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache, DB;
use App\Models\MerchantApp;
use ArrayPaginator;
class LogSohaController extends Controller
{
    public function index($page=1)
    {
        $result_total = MerchantApp::get_log_payment_soha();
         $data=MerchantApp::get_log_payment_soha();
        If(!isset($_GET['dateform'])&&!isset($_GET['dateto'])&&!isset($_GET['appid'])){
            $url='';
        }else{
            $url='?dateform='.$_GET['dateform'].'&dateto='.$_GET['dateto'].'&appid='.$_GET['appid'].'';
        }
        $url_pattern = route('log-soha') . '/(:num)'.$url.'';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $result = $paginator->getResult();

        $paginator_html = $paginator->render();
        return view('/logGD/list_log_soha',  compact('result_total','result', 'paginator_html','page'));
    }
  
    
    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $dateform=strtotime($dulieu_tu_input["date-from"]);
        $dateto=strtotime($dulieu_tu_input["date-to"]);
        $game=$dulieu_tu_input['game'];
        return redirect('/log_soha?dateform='.$dateform.'&dateto='.$dateto.'&appid='.$game.'');
        
    }
    
}

?>