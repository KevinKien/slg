<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\MerchantApp;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\DeviceUser;
use Illuminate\Support\Collection;
use Cache, Wrep\Notificato\Notificato, Storage;
use Session, Endroid\Gcm\Client;
use Redis;

class MenuController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $url ='http://api.slg.vn/apiv1/log-game';
        $listgame = json_decode(file_get_contents($url));
//        $listgame = array();
//        foreach($value as $row1){
//            $data = json_decode($row1->images);
//            $listgame[] = ["name"=>$row1->name,"image"=>$data->logo,"url_game"=>$row1->url_homepage];
//        }
//        print_r($listgame);die;
        return view('/menutop', compact('listgame'));
    }
}
