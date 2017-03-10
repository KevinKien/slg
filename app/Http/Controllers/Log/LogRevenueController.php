<?php

namespace App\Http\Controllers\Log;

use Illuminate\Http\Request;
use App\Models\Niu_log;
use App\Models\RevenueLog;
use App\Models\Merchant_app_cp;
use App\Models\LogChargeTelco;
use DateTime, DateInterval, DatePeriod;
use App\Helpers\OfficeHelper;
use Auth, Redis, Carbon\Carbon, DB;

class LogRevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)
    {
        dd('test');
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

        /*Partner*/

        $partner_id[] = "";
        if ($user->is('partner')) {
            $partner_id['0'] = $user->partner_id;
        } elseif ($user->is('deploy')) {
            if (count(Niu_log::list_partner_app($user->app_id)))
                $partner_id = Niu_log::list_partner_app($user->app_id);
        }
        $partner_list = Niu_log::list_partner($partner_id, 1);
        /*Partner*/


        /*App*/
        $app_fix[] = "";
        if ($user_type == 'partner') {
            $app_fix['0'] = $user->partner_id;
        } elseif ($user_type == 'deploy') {
            $app_fix['0'] = $user->app_id;
        }
        $appid_list = Niu_log::list_appid($app_fix, $user_type);
        /*App*/
        /*Cp*/
        $appid_choice[] = "";
        if ($user_type == 'deploy') {
            $appid_choice['0'] = $user->app_id;
        } elseif ($request->has('app_id')) {
            $appid_choice = $input['app_id'];
        } else {
            $appid_choice['0'] = $appid_list['0']->app_id;
        }
        $cpid_list = Niu_log::list_cpid($appid_choice, $partner_list, $user_type);
        /*Cp*/

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

    public function store()
    {
        $today = date('Y-m-d');
        $begin = new DateTime($today);
        $end = new DateTime($today);
        $begin->modify('-7 day');
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $range = new DatePeriod($begin, $interval, $end);

        $cps = Merchant_app_cp::all();

        foreach ($cps as $cp) {
            foreach ($range as $date) {
                $revenue = 0;
                $cpid = "";
                $key ="";
                switch ($cp->cpid) {
                    case 300000129:
                        $cpid =  'vqc';
                        $key = 'Application_REVENUE_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                    case 300000184:
                        $cpid = '17054245';
                        $key = 'Application_PAYMENT_ZING_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                    case 300000185:
                        $cpid = '18903324';
                        $key = 'Application_PAYMENT_ZING_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                    case 300000186:
                        $cpid = '17054245';
                        $key = 'Application_PAYMENT_SH_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                    case 300000187:
                        $cpid = '18903324';
                        $key = 'Application_PAYMENT_SH_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                    case 300000188:
                        $cpid = '17054245';
                        $key = 'Application_PAYMENT_FACEBOOK_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                    default:
                        $cpid = $cp->cpid;
                        $key = 'Application_REVENUE_' . $cpid . '_' . $date->format('y_m_d');
                        break;
                }

                if (Redis::exists($key)) {
                    $cache = Redis::get($key);

                    if (!empty($cache)) {
                        $transactions = json_decode($cache, true);
                        if (is_array($transactions) && !empty($transactions)) {
                            foreach ($transactions as $transaction) {
                                if (isset($transaction['amount']) && isset($transaction['uid']) && !empty($transaction['uid'])) {
                                    if($cp->cpid == 300000001){
                                        if($transaction['response']['errorCode'] == '200'){
                                            $revenue += $transaction['amount'];
                                        }
                                    }else{
                                    if ($cp->cpid == 300000185) {
                                        $revenue += $transaction['amount'] * 100;
                                    } else {
                                        $revenue += $transaction['amount'];
                                    }}
                                }
                            }
                        }
                    }
                    echo $key . ' : ' . $revenue . '<br>';
                }

                $log = RevenueLog::firstOrNew(['date' => $date->format('Y-m-d'), 'cpid' => $cp->cpid]);
                $log->revenue = $revenue;
                $log->save();
            }
        }

        return 'done.';
    }

    public function revenueTopup(Request $request)
    {
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
}