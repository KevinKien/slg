<?php

namespace App\Http\Controllers\Log;

use Illuminate\Http\Request;
use App\Models\Dau_log;
use App\Models\Niu_log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\OfficeHelper;
use DB;
use Auth;
use Carbon\Carbon;
use App\Helpers\Logs\RevenueLogHelper;

//echo Carbon::now();

class NiulogController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function get_index(Request $request) {
        $user = \Auth::user();
        if($user->is('administrator')){
            $user_type = 'admin';
        }elseif($user->is('deploy')){
            $user_type = 'deploy';
        }elseif($user->is('partner')){
            $user_type = 'partner';
        }else{
            $user_type = 'guest';
        }
        $input = $request->all();
        $results = array();
        $list_cpid = array();
        $partner_arr = array();
        $xcols = array();
        /* Date from  -  Date to */
        $datefrom = date("d-m-Y", strtotime("-8 day", time()));
        $dateto = date("d-m-Y", strtotime("-0 day", time()));


        if ($request->has('date-from')) {
            $datefrom = $input['date-from'];
            $datefromday = date("d-m", strtotime($input['date-from']));
        }
        if ($request->has('date-to')) {
            $dateto = $input['date-to'];
            $datetoday = date("d-m-Y", strtotime($input['date-to']));
        }
        $date = $datefrom;
        while (strtotime($date) <= strtotime($dateto)) {
            $xcols[] = substr($date, 0, 5);
            $date = date("d-m-Y", strtotime("+1 day", strtotime($date)));
        }
        /* Date from  -  Date to */
        
        /*Partner*/
        
        $partner_id[] = "";
        if($user->is('partner')){
            $partner_id['0'] = $user->partner_id;
         }elseif($user->is('deploy')){
            if(count(Niu_log::list_partner_app($user->app_id)))
                $partner_id = Niu_log::list_partner_app($user->app_id);
         }
        $partner_list = Niu_log::list_partner($partner_id,1);
        /*Partner*/
        

        /*App*/
        $app_fix[] = "" ;
        if($user_type == 'partner'){
            $app_fix['0'] = $user->partner_id;
        }elseif($user_type == 'deploy'){
            $app_fix['0'] = $user->app_id;
        }
        $appid_list = Niu_log::list_appid($app_fix, $user_type);
        /*App*/
        /*Cp*/
        $appid_choice[] = "";
        if($user_type == 'deploy'){
            $appid_choice['0'] = $user->app_id;
        }elseif($request->has('app_id')){
            $appid_choice = $input['app_id'];
        }  else {
            $appid_choice['0'] = $appid_list['0']->app_id;
        }
        $cpid_list = Niu_log::list_cpid($appid_choice,$partner_list,$user_type);
        /*Cp*/
        
        $list_option = array();
        $list_option['partner'] = json_decode($partner_list);
        $list_option['appid'] = $appid_list;
        $list_option['cpid'] = $cpid_list;
        ////////////////////////////////////
        
        
        if (isset($input['cp_id'])) {
            $xcols = array();
            $date = $datefrom;
            while (strtotime($date) <= strtotime($input['date-to'])) {
                $xcols[] = substr($date, 0, 5);
                $date = date("d-m-Y", strtotime("+1 day", strtotime($date)));
            }


            if (isset($input['cp_id'])) {
                $list_cpid = array();
                $list_cpid = $input['cp_id'];
            }
        }else{
                foreach ($cpid_list as $key => $cpid_list) {
                    $list_cpid[] = $cpid_list->cpid;
                }
                
        }
        foreach ($list_cpid as $key => $list) {
            $results[$key]['name'] = Niu_log::get_cp_name($list);
            foreach ($xcols as $key1 => $col) {
                $results[$key]['data'][$key1] = 0;
            }
        }
        foreach ($list_cpid as $key => $list) {
            $show_date = Niu_log::show_date($list, $datefrom, $dateto);
            foreach ($show_date as $key1 => $show) {
                $results[$key]['data'][$key1] = $show->value;
            }
        }
        $data_total = [];

        foreach($xcols as $i => $date)
        {
            $data_total[$i] = 0;
        }
        foreach ($results as $result)
        {
            foreach ($result['data'] as $k => $amount)
            {
                $data_total[$k] += $amount;
            }
        }

        $results[] = ['name' => 'Tổng', 'data' => $data_total];
        if (isset($input['xlsexport'])&($user_type =='admin'| $user_type =='deploy')) {
            $row1 = $xcols;
            array_unshift($row1, '#');
            array_push($row1, 'Peak', 'Avg');

            foreach ($results as $key => $result) {
                $exportxls['#'][] = $results[$key]["name"];
                foreach ($result['data'] as $key1 => $row) {
                    $exportxls[$row1[$key1 + 1]][] = $row;
                }
                $countdata = count($result['data']);
                $exportxls[$row1[$countdata + 1]][] = max($result['data']);
                $exportxls[$row1[$countdata + 2]][] = round(array_sum($result['data']) / count($result['data']), 2);
            }

            return OfficeHelper::exportExcel($exportxls, $input['xlsexport'] . '.xls');
        }
        return view('/Log/getNiulog', compact('results', 'list_option', 'xcols','user_type','datefromday','datetoday'));
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
    public function post_index() {
        $model_niu = new Niu_log();
        $command = $_POST['command'];
        switch ($command) {
            case 'appid' :
                $partner_id = isset($_POST['partner_id']) ? $_POST['partner_id'] : '100000001';
                $list_appid = $model_niu->list_appid($partner_id);
                echo json_encode($list_appid);
                break;
            case 'cpid':
                $appid = isset($_POST['app_id']) ? $_POST['app_id'] : '1001';
                $partner_id = isset($_POST['partner_id']) ? $_POST['partner_id'] : '100000001';
                $list_cppid = $model_niu->list_cpid($appid,$partner_id);
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
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
