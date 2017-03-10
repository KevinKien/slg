<?php

namespace App\Http\Controllers\Log;

use DB,
    Session,
    Auth;
use Cache,
    ArrayPaginator;
use Validator,
    Memcached;
use App\Http\Controllers\Controller;
use App\Models\LogCoinTransfer;
use App\Models\LogAddCoin;
use App\Http\Requests;
use Illuminate\Http\Request;

class LogAddCoinController extends Controller {

    /**
     * Display a listing of log transfer coin transaction.
     *
     * @return Response
     */                   

    public function index(Request $request, $page = 1) {

        $data = LogAddCoin::getLogAddCoins();
        $total_amount = 0;
        $count = 0;
        foreach ($data as $rows) {
            $count++;
            $total_amount += $rows->amount;
        }
        $input = $request->all();

        If (!isset($input['dateform']) && !isset($input['dateto'])) {
            $url = '';
        } else {
            $url = '?dateform=' . $input['dateform'] . '&dateto=' . $input['dateto'] . '';
        }
        $url_pattern = route('log-add-coin') . '/(:num)' . $url . '';

        $paginator = new ArrayPaginator($data, $page, $url_pattern);

        $result = $paginator->getResult();
        
        $paginator_html = $paginator->render();

        return view('/Log/log_add_coin', compact('$data', 'total_amount', 'count', 'result', 'paginator_html', 'page'));
    }

    public function searchLogAddCoins(Request $request) 
    {
        $dulieu_tu_input = $request->all();
        $dateform = strtotime($dulieu_tu_input["date-from"]);
        $dateto = strtotime($dulieu_tu_input["date-to"]);        

        return redirect('/add-coin-log?dateform=' . $dateform . '&dateto=' . $dateto);
    }

}
