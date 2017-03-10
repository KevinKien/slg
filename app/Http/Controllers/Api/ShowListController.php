<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Dau_log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\MerchantApp;
use Illuminate\Support\Facades\Redis,
    Response;
use DB;
use Carbon\Carbon;
//echo Carbon::now();

class ShowListController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (isset($_GET['date']) & isset($_GET['cpid'])) {
            $key = LOG_DAU_USER . "_" . $_GET['cpid'] . "_" . $_GET['date'];
            $dau_get = Redis::get($key);
            $date_log = $_GET['date'];
            $cpid_log = $_GET['cpid'];
            if ($dau_get > 0) {
                $dau_update_query = DB::table('dau_log')
                        ->where('name', '=', $date_log)
                        ->get();

                if (count($dau_update_query) == 0) {
                    $dau_insert = new Dau_log;
                    $dau_insert->name = $date_log;
                    $dau_insert->value = $dau_get;
                    $dau_insert->cpid = $cpid_log;
                    $dau_insert->save();
                }
                else{
                    $dau_update = DB::table('dau_log')
                            ->where('name',$date_log )
                            ->update(['value' => $dau_get]);
                }
            }
        } else {
            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getListGame(){
        return Response::json(MerchantApp::getGameListApi());
    }
    public function getLogUser(){
        for($i=0;$i<30;$i++){
            $cp_id = '300000000';
            $date = date("y_m_d", strtotime("-".$i." day", time()));
            $key =  LOG_DAU_USER . "_" . $cp_id. "_" . $date;
//            echo Redis::get($key);
//            echo "/";
            
        }
        $datefrom = '12-08-2015';
            $dateto = '18-08-2015';
            $datediff = abs(strtotime($dateto) - strtotime($datefrom));
            $numdate = floor($datediff/(60*60*24));
            for($i = 0; $i<= $numdate;$i++){
                echo date("d_m", strtotime("+".$i." day", strtotime($datefrom)));
                echo "/";
            }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $model_dau = new Dau_log();
        $command = $_POST['command'];
        switch ($command) {
            case 'appid' :
                $partner_id = isset($_POST['partner_id'])?$_POST['partner_id']:'100000001';
                $list_appid = $model_dau->list_appid($partner_id);
                echo json_encode($list_appid);
                break;
            case 'cpid':
                $appid = isset($_POST['app_id'])?$_POST['app_id']:'1001';
                $list_cppid = $model_dau->list_cpid($appid);
                echo json_encode($list_cppid);
                break;
        }
        

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
