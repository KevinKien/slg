<?php
namespace App\Http\Controllers\ApiDevice;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use App\Models\NotifyGameMobile;
use ArrayPaginator;
use CommonHelper;
use Response;

class ApiDeviceByUserController extends Controller
{
    public function index()
    {
        $appid = $_GET['appid'];
        $user = $_GET['uid'];
        $result = NotifyGameMobile::get_all_log_device_by_user($appid, $user);
        If ($result == '') {
            $status = 31;
        } else
            $status = 200;
        return reponse_data($status, $result);
    }

    function reponse_data($status, $result)
    {
        $response['errorCode'] = $status;
        $response['errorMessage'] = CommonHelper::convertErrorCode($status);
        $response['data'] = $result;
        return Response::json($response);
    }
}