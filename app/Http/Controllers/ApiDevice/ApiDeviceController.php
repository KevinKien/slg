<?php
namespace App\Http\Controllers\ApiDevice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache;
use App\Models\NotifyGameMobile;
use ArrayPaginator;
use CommonHelper;
use Response;
class ApiDeviceController extends Controller
{
    public function index()
    {
         $appid = $_GET['appid'];
         $result = NotifyGameMobile::get_all_log_device($appid);
         If($result ==''){
             $status=31;
         }  else
         $status = 200;
        return reponse_data($status, $result);
    }
}    
function reponse_data($status,$result)
	{		
		$response['errorCode'] =$status;
		//print_r($status);die;
		$response['errorMessage'] = CommonHelper::convertErrorCode($status);
		$response['data'] = $result;
		$json_response = json_encode($response);
                 return Response::json($response);	
	}

?>