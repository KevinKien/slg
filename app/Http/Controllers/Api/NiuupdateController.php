<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Dau_log;
use App\Models\Niu_log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
//echo Carbon::now();
use App\Helpers\Logs\UtilHelper;
class NiuupdateController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $day = 3;
        $type = 1;
        if (isset($_GET['cpid'])) {
            switch ($_GET['cpid']) {
                case '300000129':
                $cpid ='vqc';
                break;
                case '300000141':
                $type = 2;
                $cpid =$_GET['cpid']; 
                break;
                case '300000143':
                $type = 2;
                $cpid =$_GET['cpid']; 
                break;
                case '300000177':
                $type = 2;
                $cpid =$_GET['cpid']; 
                break;
                case '300000180':
                $type = 2;
                $cpid =$_GET['cpid']; 
                break;
                default:
                $cpid =$_GET['cpid'];    
                break;
            }
            for ($i = 0; $i <= $day; $i++) {
                $date = date("y_m_d", strtotime("-".$i." day", time()));
                $key = LOG_NIU. "_" . $cpid . "_" . $date;
                    if ($type == 2) {
                        $dau_get = UtilHelper::getredis($key, 'log');
                    } else {
                    $dau_get = UtilHelper::getredis($key);
                    }
                $date_log = date("ymd", strtotime("-".$i." day", time()));
                $cpid_log = $_GET['cpid'];
                if (!empty($dau_get)) {
                    $dau_update_query = DB::table('niu_log')
                            ->where('name',  $date_log)
                            ->where('cpid',  $cpid_log)
                            ->get();

                    if (count($dau_update_query) == 0) {
                        $niu_insert = new Niu_log;
                        $niu_insert->name = $date_log;
                        $niu_insert->value = $dau_get;
                        $niu_insert->cpid = $cpid_log;
                        $niu_insert->save();
                    } else {
//                        echo $date_log."/".$dau_get."\n";
                        $dau_update = DB::table('niu_log')
                                ->where('cpid',  $cpid_log)
                                ->where('name', $date_log)
                                ->update(['value' => $dau_get]);
                    }
                }else{
                    $dau_update_query = DB::table('niu_log')
                            ->where('name',  $date_log)
                            ->where('cpid',  $cpid_log)
                            ->get();
                    if (count($dau_update_query) == 0) {
                        $niu_insert = new Niu_log;
                            $niu_insert->name = $date_log;
                            $niu_insert->value = 0;
                            $niu_insert->cpid = $cpid_log;
                            $niu_insert->save();
                    }
                }
                echo "Done".$cpid_log.$date_log;
            }
        } else {
            
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
