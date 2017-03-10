<?php

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use App\Models\Niu_log;
use App\Models\RevenueLog;
use App\Models\Merchant_app_cp;
use App\Models\LogChargeTelco;
use DateTime,
    DateInterval,
    DatePeriod;
use App\Helpers\OfficeHelper;
use Auth,
    Redis,
    Carbon\Carbon,
    DB;

class TaskRevenueController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        dd('test');
    }
}
