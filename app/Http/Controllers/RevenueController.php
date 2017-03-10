<?php

namespace App\Http\Controllers;

use App\Models\CashInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Niu_log;
use App\Models\RevenueLog;
use App\Models\Merchant_app_cp;
use App\Models\LogChargeTelco;
use App\Models\LogCoinTransfer;
use App\Models\LogCoinRestore;
use App\Models\LogBuyItemSoha;
use App\Models\LogBuyItemZing;
use App\Http\Controllers\Payment\Partner\AtmCardBanknet;
use DateTime,
    DateInterval,
    DatePeriod;
use App\Helpers\OfficeHelper;
use App\Helpers\RedisKeyHelper;
use Auth,
    Redis,
    Carbon\Carbon,
    Input,
    DB;

class RevenueController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        
        $user = \Auth::user();
        if ($user->is('administrator')) {
            $user_type = 'admin';
        } elseif ($user->is('deploy')) {
            $user_type = 'deploy';
        } elseif ($user->is('partner')) {
            $user_type = 'partner';
        } else {
            $user_type = 'guest';
        }
        $input = $request->all();
        $results = array();
        $list_cpid = array();
        $date_range = array();
        /* Date from  -  Date to */
        $datefrom = date("d-m-Y", strtotime("-7 day", time()));
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

        /* Partner */

        $partner_id[] = "";
        if ($user->is('partner')) {
            $partner_id['0'] = $user->partner_id;
        } elseif ($user->is('deploy')) {
            if (count(Niu_log::list_partner_app($user->app_id)))
                $partner_id = Niu_log::list_partner_app($user->app_id);
        }
        $partner_list = Niu_log::list_partner($partner_id, 1);
        /* Partner */


        /* App */
        $app_fix[] = "";
        if ($user_type == 'partner') {
            $app_fix['0'] = $user->partner_id;
        } elseif ($user_type == 'deploy') {
            $app_fix['0'] = $user->app_id;
        }
        $appid_list = Niu_log::list_appid($app_fix, $user_type);
        /* App */
        /* Cp */
        $appid_choice[] = "";
        if ($user_type == 'deploy') {
            $appid_choice['0'] = $user->app_id;
        } elseif ($request->has('app_id')) {
            $appid_choice = $input['app_id'];
        } else {
            $appid_choice['0'] = $appid_list['0']->app_id;
        }
        $cpid_list = Niu_log::list_cpid($appid_choice, $partner_list, $user_type);
        /* Cp */

        $list_option = array();
        $list_option['partner'] = json_decode($partner_list);
        $list_option['appid'] = $appid_list;
        $list_option['cpid'] = $cpid_list;
        ////////////////////////////////////
        $date_range = array();
        $date = $datefrom;
        while (strtotime($date) <= strtotime($dateto)) {
            $date_range[] = $date;
            $date = date("d-m-Y", strtotime("+1 day", strtotime($date)));
        }
        if (isset($input['cp_id'])) {


            if (isset($input['cp_id'])) {
                $list_cpid = $input['cp_id'];
            }
        } else {
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
        $first = new Datetime(reset($date_range));
        $end = new Datetime(end($date_range));

        $logs = RevenueLog::whereIn('cpid', $list_cpid)
                ->whereBetween('date', [$first->format('Y-m-d'), $end->format('Y-m-d')])
                ->get();
        foreach ($logs as $log) {
            $results[array_search($log->cpid, $list_cpid)]['data'][array_search(date('d-m-Y', strtotime($log->date)), $date_range)] = $log->revenue;
        }

        $data_total = [];

        foreach ($date_range as $i => $date) {
            $data_total[$i] = 0;
        }
        foreach ($results as $result) {
            foreach ($result['data'] as $k => $amount) {
                $data_total[$k] += $amount;
            }
        }

        $total_revenue = array_sum($data_total);

        $results[] = ['name' => 'Tổng', 'data' => $data_total];

        if (isset($input['xlsexport']) & ($user_type == 'admin' | $user_type == 'deploy')) {
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
        return view('revenue.index', compact('results', 'list_option', 'xcols', 'date_range', 'user_type', 'total_revenue', 'datefromday', 'datetoday'));
    }

    public function store() {
        $today = date('Y-m-d');
        $begin = new DateTime($today);
        $end = new DateTime($today);
        $begin->modify('-7 day');
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $range = new DatePeriod($begin, $interval, $end);

        $cps = Merchant_app_cp::AllCp();
        foreach ($cps as $cp) {
            foreach ($range as $date) {
                $revenue = 0;
                $revenue_db = 0;
                $cpid = "";
                $key = "";
                $cpid = "";
                $key = "";
                $cpid = $cp->cpid;
                $date1 = $date->format('Y-m-d');
                $key = RedisKeyHelper::getRevenueKey($cpid, $date1);
                $revenue = RedisKeyHelper::updateRevenueKey($key, $cpid);

                if ($cp->check_revenue == 1 && $cp->partner_id == '100000010') {
                    $revenue_db = LogCoinTransfer::getGameDailyRevenue($cp->app_id, $date1);
                    $revenue_db = $revenue_db['revenue'] * 100;
                }
                if ($cp->check_revenue == 1 && $cp->partner_id == '100000024') { // soha
                    if($cp->cpid == '300000193' && $cp->check_revenue == 1){
                        $revenue_db = LogBuyItemSoha::getGameDailyRevenue($cp->cpid, $date1);
                        $revenue_db = $revenue_db['revenue'] * 100; // soha coins
                    }else{
                    $revenue_db = LogBuyItemSoha::getGameDailyRevenue($cp->cpid, $date1);
                    $revenue_db = $revenue_db['revenue'] * 1000; // soha coins
                    }
                    //echo $revenue_db . '<br/>';
                }
                if ($cp->check_revenue == 1 && $cp->partner_id == '100000009') { // zing
                    $revenue_db = LogBuyItemZing::getGameDailyRevenue($cp->cpid, $date1);
                    $revenue_db = $revenue_db['revenue']; // zing coins
                    //echo $revenue_db . '<br/>';
                }
                echo $key . ' : ' . $revenue . ' (' . $revenue_db . ')' . '<br>';
                if($cp->cpid == '300000193'){
                    $save_revenue = $revenue_db;
                }else{
                $save_revenue = ($revenue_db >= $revenue) ? $revenue_db : $revenue;
                }
                if ($save_revenue > 0) {
                    $log = RevenueLog::firstOrNew(['date' => $date->format('Y-m-d'), 'cpid' => $cp->cpid]);
                    if ($save_revenue >= $log->revenue)
                        $log->revenue = $save_revenue;
                    $log->save();
                }
            }
        }

        return 'done.';
    }

    public function revenueTopup(Request $request) {

        $results = [];
        $date_range = [];
        $total_revenue = 0;
        $preset_date = null;

        if ($request->method() == 'GET') {
            $from = Carbon::today()->subDays(6);
            $to = Carbon::today();
        } else {

            $this->validate($request, [
                'date' => 'required'
            ]);

            $date = explode(' - ', $request->input('date'));

            $from = Carbon::createFromFormat('d/m/Y', $date[0]);
            $to = Carbon::createFromFormat('d/m/Y', $date[1]);
        }

        $data = LogChargeTelco::whereDate('created_at', '>=', $from->toDateString())->whereDate('created_at', '<=', $to->toDateString())
                ->where(function ($query) {
                    $query->where('payment_status', '=', 'success')
                    ->orWhere('payment_status', 'LIKE', '%Giao dịch thành công%');
                })
                ->where('partner_type', '<>', 'test')
                ->get();

        if (!$data->isEmpty()) {
            $preset_date = $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y');

            $interval = new DateInterval('P1D');
            $to->add($interval);
            $range = new DatePeriod($from, $interval, $to);

            $total = [];

            foreach ($range as $date) {
                $_date = $date->format('d/m/Y');
                $date_range[] = $_date;
                $total[$_date] = 0;
            }

            $types = [
                'atm' => 'ATM',
                'visa' => 'VISA',
                'VT' => 'Viettel',
                'VTT' => 'Viettel',
                'VNP' => 'Vinaphone',
                'VINA' => 'Vinaphone',
                'VMS' => 'Mobifone',
                'MOBI' => 'Mobifone',
                'FPT' => 'FPT Gate',
                'GATE' => 'FPT Gate',
                'VCOIN' => 'VTC',
                'ZING' => 'ZING',
                'NganLuong' => 'Ngân Lượng ATM',
                'CYBERPAY' => 'CyberPay',
            ];

            foreach ($data as $row) {
                foreach ($date_range as $date) {
                    if (!isset($results[$types[$row->card_type]]['data'][$date])) {
                        $results[$types[$row->card_type]]['data'][$date] = 0;
                    }

                    $_date = date('d/m/Y', strtotime($row->created_at));

                    if ($_date == $date) {
                        $results[$types[$row->card_type]]['data'][$date] += $row->amount;
                    }
                }
            }

            foreach ($results as $name => $result) {
                $results[$name]['name'] = $name;
                foreach ($result['data'] as $date => $amount) {
                    $total[$date] += $amount;
                }
            }

            $total_revenue = array_sum($total);

            $results[] = ['name' => 'Tổng', 'data' => $total];
        }

        return view('revenue.topup', compact('results', 'date_range', 'total_revenue', 'preset_date'));
    }

    public function getCheckAtmTrans() {
        $rs = LogChargeTelco::getOrderAtmTransByDay();
        $result = array();
        $transid = 0;
        foreach ($rs as $trans) {
            if (!empty($trans->trans_id)) {
                $transid = $trans->trans_id;
                //fix
                //$transid = 'SML_4767047_1459310224_1541196855';
                for ($i = 0; $i < 5; $i++) {
                    $trans_bn = AtmCardBanknet::checkAtmCardBanknetTransaction($transid);
                    if (is_object($trans_bn) && isset($trans_bn->vpc_TxnResponseCode) && ($trans_bn->vpc_TxnResponseCode == '00' || $trans_bn->vpc_TxnResponseCode == '0' )) {
                        break;
                    }
                    sleep(3);
                }

                $result[] = $trans_bn;
                if (is_object($trans_bn) && !empty($trans_bn->vpc_DRExists) && $trans_bn->vpc_DRExists == 'Y') {
                    // nap thanh cong
                    $order = LogChargeTelco::getLogChargeCardByTransid($transid);
                    if (is_object($order) && isset($order->uid) && isset($order->amount) && $order->amount > 0) {
                        $amount = $order->amount;
                        $uid = $order->uid;
                        $coin = $amount / 100; // 10k vnd => 100 coin
                        // update coin vao vi
                        $update_coin_status = CashInfo::incrementCoin($uid, $coin);

                        // set msg to step 3
                        $msg = 'Nạp thẻ thành công.';

                        //update log
                        if ($update_coin_status) {
                            $order->coin = $coin;
                            $order->payment_status = 'success';
                            //$order->response = json_encode($request->all());
                            if ($order->save()) {
                                // update log bu xu
                                $log = new LogCoinRestore();
                                $log->admin = 'robot';
                                $log->user = $trans->uid;
                                $log->amount = $amount;
                                $log->coins = $coin;
                                $log->trans_id = $transid;
                                $log->save();
                            }
                        }
                    }
                }
            }
        }

        // cache time run crontab
        $cache_key = 'atm_check_trans';
        $time = 49 * 60 * 60;
        $date = Carbon::now();

        //dd(Cache::has($cache_key));
        if (!Cache::has($cache_key)) {
            //Cache::add($cache_key, $date, $time);
        } else {
            //Cache::put($cache_key, $date, $time);
        }

        dd($rs);
    }

    public function getLogrevenuecpi() {
        $data = Input::get('data');
        $data = json_decode(base64_decode($data));
        $partner = isset($data->partner) ? $data->partner : 0;

        if (Input::has('test')) {
            dd($partner);
        }


        if ($partner == '100000024') {  // partner soha
            $order_id = $data->soha_order_info;
            $log = \App\Models\LogBuyItemSoha::firstOrNew(['soha_order_info' => $order_id]);
            $log->cpid = $data->cpid;
            $log->userid = $data->uid;
            $log->item_price = $data->amount;
            $log->server_id = $data->serverid;
            $log->soha_order_info = $data->soha_order_info;
            $log->status = 3;
            $log->channel_id = 'soha';
            $log->request_time = date('Y-m-d H:i:s', $data->time);
            $log->save();
            //dd($log);
        } elseif ($partner == '100000009') {   // zing
            $order_id = $data->zing_order_info;
            $log = \App\Models\LogBuyItemZing::firstOrNew(['zing_order_info' => $order_id]);
            $log->cpid = $data->cpid;
            $log->userid = $data->uid;
            $log->item_price = $data->amount;
            $log->server_id = $data->serverid;
            $log->zing_order_info = $data->zing_order_info;
            $log->status = 3;
            $log->channel_id = 'zing';
            $log->request_time = date('Y-m-d H:i:s', $data->time);
            $log->save();
            //dd($log);
        }
        echo 'done';
    }

}
