<?php

namespace App\Http\Controllers\Util;

use DB, Session, Auth, Carbon\Carbon;
use Cache, ArrayPaginator, App\Models\User;
use Validator, Memcached;
use App\Http\Controllers\Controller;
use App\Models\LogCoinTransfer;
use App\Models\LogChargeTelco;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\Logs\LaravelLogHelper;
use CommonHelper;


class LogController extends Controller
{
    /**
     * Display a listing of log transfer coin transaction.
     *
     * @return Response
     */

    public function getLogTopup()
    {
        $logs = LogChargeTelco::getLog(Auth::id());
        return view('frontend.topup-history', ['logs' => $logs]);
    }

    public function getLogTransferCoin()
    {
        $logs = LogCoinTransfer::getLog(Auth::id());
        return view('frontend.topcoin-history', ['logs' => $logs]);
    }

    public static function logChargeCoin($uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, $response, $coin, $amount, $ip, $transid_partner, $partner, $msg)
    {
        $log = new LogChargeTelco();
        $log->uid = $uid;
        $log->trans_id = $transid;
        $log->card_code = $card_code;
        $log->card_seri = $card_seri;
        $log->card_type = $card_type;
        $log->order_mobile = $order_mobile;
        $log->response = json_encode($response);
        $log->coin = $coin;
        $log->amount = $amount;
        $log->ip = $ip;
        $log->partner_trans_id = $transid_partner;
        $log->partner_type = $partner;
        $log->payment_status = $msg;
        $log->save();

        return $log;
    }

    public static function logChargeCard($arr)
    {
        $log = new LogChargeTelco();
        $log->uid = $arr['uid'];
        $log->trans_id = $arr['trans_id'];
        $log->card_type = $arr['card_type'];
        $log->amount = $arr['amount'];
        $log->partner_type = $arr['partner_type'];
        $log->payment_status = 'order';
        $log->ip = CommonHelper::getClientIP();
        $log->access_token = isset($arr['access_token']) && $arr['access_token'] ? $arr['access_token'] : '';
        $log->save();
        return $log;
    }

    public function getRequestLog($page = 1)
    {
        $m = new Memcached();

        $config = config('cache.log-request');
        $m->addServer($config['host'], $config['port']);

        $keys = $m->getAllKeys();
        $log_keys = [];

        foreach ($keys as $key) {
            if (strpos($key, 'slg_log_request') !== false) {
                $log_keys[] = $key;
            }
        }

        rsort($log_keys);

        $result = $m->getMulti($log_keys);

        $paginator = new ArrayPaginator($result, $page, route('request-log') . '/(:num)');

        $logs = $paginator->getResult();

        $paginator_html = $paginator->render();

        if (Cache::has('settings_request_log') === false || Cache::get('settings_request_log') == 0) {
            Session::flash('flash_info', 'Log Request đang bị tắt.');
        }

        return view('Log.log_request', ['logs' => $logs, 'paginator_html' => $paginator_html]);
    }

    public function clearRequestLog()
    {
        $m = new Memcached();

        $config = config('cache.log-request');
        $m->addServer($config['host'], $config['port']);

        $keys = $m->getAllKeys();
        $log_keys = [];

        foreach ($keys as $key) {
            if (strpos($key, 'slg_log_request') !== false) {
                $log_keys[] = $key;
            }
        }

        $deleted = $m->deleteMulti($log_keys);

        if ($deleted) {
            Session::flash('flash_success', 'Xóa Log thành công.');
        } else {
            Session::flash('flash_error', 'Lỗi: không thể xóa được Log.');
        }

        return redirect()->route('request-log');
    }

    public function getIndexLogTransferCoin()
    {
        return view('Log.log_transfer_coin');
    }

    public function getAllLogTransferCoin(Request $request)
    {
        $this->validate($request, [
            'uid' => 'required|min:3|max:128'
        ]);

        $uid = $request->get('uid');

        if (!is_numeric($uid)) {
            $user = User::where('name', $uid)->first();

            if ($user) {
                $uid = $user->id;
            }
        }

        $logs = LogCoinTransfer::where('user_id', $uid);
        
        if ($request->has('date')) {
            $date = explode(' - ', $request->get('date'));

            $start = Carbon::createFromFormat('d/m/Y', $date[0])->toDateString();
            $end = Carbon::createFromFormat('d/m/Y', $date[1])->toDateString();

            $logs = $logs->whereDate('request_time', '>=', $start)->whereDate('request_time', '<=', $end);
        }

        $logs = $logs->join('merchant_app', function ($join) {
            $join->on('log_coin_transfer.app_id', '=', 'merchant_app.id');
        })->leftJoin('merchant_app_config', function ($join) {
            $join->on('log_coin_transfer.server_id', '=', 'merchant_app_config.serverid');
            $join->on('log_coin_transfer.app_id', '=', 'merchant_app_config.appid');
        });

        if ($request->has('status')) {
            $logs = $logs->where('status', $request->get('status'));
        }

        $logs = $logs->select('log_coin_transfer.*', 'merchant_app_config.servername', 'merchant_app.name')
            ->orderBy('request_time', 'desc')->paginate(10);

        return view('Log.log_transfer_coin', ['logs' => $logs]);
    }

    public function index(Request $request, $page = 1) {
        
        $input = $request->all();
        $arr = array();
        If (!isset($input['dateform']) && !isset($input['dateto']) && !isset($input['username']) && !isset($input['serial']) && !isset($input['transaction_id']) && !isset($input['card_type']) && !isset($input['status'])) {                        
            $arr['dateform'] = strtotime(date('Y-m-d', time()) . ' 00:00:00');
            $arr['dateto'] = strtotime(date('Y-m-d', time()) . ' 23:59:59');
            $arr['username'] = '';
            $arr['serial'] = '';
            $arr['transaction_id'] = '';
            $arr['card_type'] = '';
            $arr['status'] = '';
            $arr['amount'] = '';
            $url = '';                        
        } else {                        
            $arr['dateform'] = strtotime(date('Y-m-d', $input['dateform']) . ' 00:00:00');
            $arr['dateto'] = strtotime(date('Y-m-d', $input['dateto']) . ' 23:59:59');        
            $arr['username'] = $input['username'];
            $arr['serial'] = $input['serial'];
            $arr['transaction_id'] = $input['transaction_id'];
            $arr['card_type'] = $input['card_type'];
            $arr['status'] = $input['status'];
            $arr['amount'] = $input['amount'];
            $url = '?dateform=' . $input['dateform'] . '&dateto=' . $input['dateto'] . '&username=' . $input['username'] . '&serial=' . $input['serial'] . '&transaction_id=' . $input['transaction_id'] . '&card_type=' . $input['card_type'] . '&status=' . $input['status'] . '&amount=' . $input['amount'] .'';    
        }
        $url_pattern = route('log-GD') . '/(:num)' . $url . '';
        
        $data = LogChargeTelco::getLogGDTelco($arr);
        $total_amount = 0;
        $count = 0;
        foreach ($data as $rows) {
            $count++;
            $total_amount += $rows->amount;
        }
        
        $paginator = new ArrayPaginator($data, $page, $url_pattern);
        
        $result = $paginator->getResult();

        $paginator_html = $paginator->render();
        
        return view('/Log/search_log_transaction', compact('total_amount', 'count', 'result', 'paginator_html', 'page'));
    }

    public function searchLogGD(Request $request) {
        $dulieu_tu_input = $request->all();        
        $dateform = strtotime($dulieu_tu_input["date-from"]);
        $dateto = strtotime($dulieu_tu_input["date-to"]);
        $username = $dulieu_tu_input['username'];
        $serial = $dulieu_tu_input['serial'];
        $transaction_id = $dulieu_tu_input['transaction_id'];
        $card_type = $dulieu_tu_input["card_type"];
        $status = $dulieu_tu_input["status"];
        $amount = $dulieu_tu_input["amount"];
        
        return redirect('/logGD?dateform=' . $dateform . '&dateto=' . $dateto . '&username=' . $username . '&serial=' . $serial . '&transaction_id=' . $transaction_id . '&card_type=' . $card_type . '&status=' . $status . '&amount=' . $amount .'');
    }
    
    public function laravel(Request $request)
    {
        if ($request->has('l')) {
            LaravelLogHelper::setKey(base64_decode($request->get('l')));
        }

        LaravelLogHelper::setClient(config('redis-logger'));

        if ($request->has('del')) {
            LaravelLogHelper::deleteKey(base64_decode($request->get('del')));
            return redirect()->route('laravel-log');
        }

        $logs = LaravelLogHelper::allRedis();
        $files = LaravelLogHelper::getKeys();
        $current_file = LaravelLogHelper::getKeyName();

        return view('Log.log_laravel', compact('logs', 'files', 'current_file'));
    }
}
