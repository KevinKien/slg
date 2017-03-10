<?php
namespace App\Http\Controllers\Api\ThienLong\V1;

use Route, Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceUser;

class DeviceController extends Controller
{
    public function getStore(Request $request)
    {
        $_request = Request::create(route('store-device-token'), 'GET', $request->all());

        return Route::dispatch($_request)->getContent();
    }

    public function getLog(Request $request)
    {
        if ($request->has('client_id')) {
            $response['errorCode'] = 200;

            $_logs = DeviceUser::where('client_id', $request->input('client_id'));

            if ($request->has('uid'))
            {
                $_logs = $_logs->where('uid', $request->input('uid'));
            }

            $logs = $_logs->get(['os_id', 'device_id']);

            $response['data'] = $logs->isEmpty() ? '' : $logs->toArray();

        } else {
            $response['errorCode'] = 2;
        }

        return json_encode($response);
    }
}