<?php
namespace App\Http\Controllers\ApiDevice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache;
use App\Models\NotifyGameMobile;
use ArrayPaginator;
use DB;
use Response;
use CommonHelper;
class ApiSaveDeviceTokenController extends Controller
{
    public function index()
    {
         $appid = $_GET['appid'];
         $user=$_GET['uid'];
         $os_id=$_GET['os_id'];
         $device_id=$_GET['device_id'];
         $result_data = NotifyGameMobile::save_device_token($user, $os_id, $device_id, $appid);
         $result=  DB::table('notify_game_mobile')->where('os_id','=', $os_id)->where('device_id','=', $device_id)->get();
         $status = $result_data; //Trả về trạng thái
        return reponse_data($status, $result);
    }
}
        function reponse_data($status,$result)
	{		
		$response['errorCode'] =$status;
		//print_r($status);die;
		$response['errorMessage'] = CommonHelper::convertErrorCode($status);
		$response['data'] = $result;
                return Response::json($response);
//		$json_response = json_encode($response);	
		//echo "1";		
//		header("Content-type: application/json; charset=utf-8");
//		print_r($json_response);	
	}

?>