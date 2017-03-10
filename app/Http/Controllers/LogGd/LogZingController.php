<?php
namespace App\Http\Controllers\LogGd;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cache;
use App\Models\MerchantApp;
use ArrayPaginator;
use Response,DB;
class LogZingController extends Controller
{
    public function index($page=1)
    {
        $result_total=MerchantApp::get_log_payment_zing();
        $data=MerchantApp::get_log_payment_zing();
//        $data2 = MerchantApp::get_total_amount_log_payment_zing();

        if(!isset($_GET['appid'])){
            $appid=18903324;
        }  else {
            $appid=$_GET['appid'] ;
        }
        // id 17054245 = T? ho�ng ??i chi?n , id 18903324 = Managa ??i chi?n
        $array = [
            ['merchantapp' => ['id' => 17054245, 'name' => 'T&#7913; ho&#224;ng &#273;&#7841;i chi&#7871;n']],
            ['merchantapp' => ['id' => 18903324, 'name' => 'Managa &#273;&#7841;i chi&#7871;n']],
        ];
        $data1 = array_pluck($array, 'merchantapp.name', 'merchantapp.id');
        If(!isset($_GET['dateform'])&&!isset($_GET['dateto'])&&!isset($_GET['appid'])){
            $url='';
        }else{
            $url='?dateform='.$_GET['dateform'].'&dateto='.$_GET['dateto'].'&appid='.$_GET['appid'].'';
        }
        $url_pattern = route('log-zing') . '/(:num)'.$url.'';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $result = $paginator->getResult();

        $paginator_html = $paginator->render();
        return view('/logGD/list_log_zing',  compact('result_total','result', 'paginator_html','page','data1','appid'));
    }
  
    
    public function store(Request $request)
    {
        $dulieu_tu_input = $request->all();
        $dateform=strtotime($dulieu_tu_input["date-from"]);
        $dateto=strtotime($dulieu_tu_input["date-to"]);
        $game=$dulieu_tu_input['game'];
        return redirect('/log_zing?dateform='.$dateform.'&dateto='.$dateto.'&appid='.$game.'');
        
    }
    
}

?>