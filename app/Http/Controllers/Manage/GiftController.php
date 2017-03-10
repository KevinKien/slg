<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\MerchantApp;
use App\Models\MerchantAppConfig;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DateTime;
use App\Models\Event_game;
use App\Models\GiftCodeServer;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEvent()
    {
        //
        $event = Event_game::where('status',1)->get();
        return view("gift.list",['event'=>$event]);
    }

    public function  addEvent(){
        $games = MerchantApp::where('status',1)->get();

        return view('gift.add_event',['games'=>$games]);
    }

    public function AjaxGame (Request $req){
        $id_game = $req->id_games;
        $games = MerchantApp::find($id_game);
        if($games){
            $server_game = MerchantAppConfig::where('appid',$games->id)->where('partner_id',0)->where('status_server',1)->get();
            $html = view("gift.ajax_gift_game",['server_game'=>$server_game]);
            return json_encode(array('status'=>true,'html'=>$html.""));
        }else{
            return "Có lỗi xảy ra";
        }
    }

    /**
     * @param Request $req
     */
        public function PostEvent(Request $req){
            $events = new Event_game;
       $validator = Validator::make($req->all(),[
           'eventname' => 'required',
           'description' => 'required',
           'timeMin' => 'required',
           'timeMax' => 'required',
           'image' => 'required|mimes:jpeg,jpg,png,gif',
       ],[
           'eventname.required'=>'Tên event bắt buộc phải nhập',
           'description.required'=>'Tiêu đề bắt buộc phải nhập',
           'timeMin.required'=>'Thời gian bắt đầu bắt buộc phải nhập',
           'timeMax.required'=>'Thời gian kết thúc bắt buộc phải nhập',
           'image.mimes'=>'File ảnh chưa đúng định dạng : jpeg,jpg,png,gif',
           'image.required'=>'File ảnh bắt buộc phải nhập',

       ]);

       if($validator->fails()){
           return redirect()->back()->withInput()->withErrors($validator);
       }else {
           $events->name = $req->eventname;
           $events->description = $req->description;
                $date_Min = $req->timeMin;
                $convert_date_min = date("Y-m-d H:i:s", strtotime($date_Min));
           $events->time_min = $convert_date_min;
                $date_Max = $req->timeMax;
                $convert_date_max = date("Y-m-d H:i:s", strtotime($date_Max));
           $events->time_max = $convert_date_max;
           // image
            if($req->hasFile('image')){
                  $file = Input::file('image');
                  $nameimg  =  date('d-m-Y').".".$file->getClientOriginalName();
                  $file->move(public_path().'/image_event/', $nameimg);
                  $events->image = '/image_event/'.$nameimg;
            }
           // link share
            $events->link_share = $req->share;
            // link thông tin
            $events->link_information = $req->image_thongtin;
           //checkbox
          $checkbox = $req->checkbox;
          if($checkbox == 1){
              $events->status = 1;
          }else{
              $events->status = 0;
          }
          // code_type
           $even_type = $req->choose_event;
            switch ($even_type){
                case "1" :
                    $events->giftcode_type = $even_type; // 1 code dành cho nhiều người dùng
                    $validator1 = Validator::make($req->all(),[
                        'gift_code_text' => 'required'
                    ],[
                        'gift_code_text.required'=>'Nội dung gift code bắt buộc phải nhập'
                    ]);
                    if($validator1->fails()){
                        return redirect()->back()->withInput()->withErrors($validator1);
                    }else{
                        $gift_code_text =trim($req->gift_code_text);
                    }
                    break;
                case "2" :
                    $events->giftcode_type = $even_type; // 1 code dành cho 1 người dùng
                    // txt
                    $validator2 = Validator::make($req->all(),[
                        'file_txt' => 'required|mimes:txt'
                    ],[
                       'file_txt.required'=>'File gift code bắt buộc phải nhập',
                       'file_txt.mimes'=>'File không đúng định dạng txt'

                    ]);
                    if($validator2->fails()){
                        return redirect()->back()->withInput()->withErrors($validator2);
                    }else{
                       if ($req->hasFile('file_txt')) {
                           $file = Input::file('file_txt');
                           $f = fopen("$file", "r");
                           $line_of_text = array();
                           while (!feof($f)) {
                               $line_of_text[] = str_replace(array("\r", "\n"), '', fgets($f));
                           }
                            $code = array_unique($line_of_text);
                           $gift_code = array();
                           foreach ($code as $item){
                               if($item != ""){
                                   array_push($gift_code,$item);
                               }
                           }
                           fclose($f);
                       }
                    }
                    break;
                default: return redirect()->back()->withInput()->withErrors([
                    'error' => 'Vui lòng chọn loại event',
                ]);
            }
           // game
            $game_id = (int)$req->choose_game;
            if($game_id == 0){
                return redirect()->back()->withInput()->withErrors([
                    'error' => 'Vui lòng chọn Game thực hiện event',
                ]);
            }
           // server
           $server = $req->choose_sever;
           if($server == null){
               return redirect()->back()->withInput()->withErrors([
                    'error' => 'Chưa có server nào được chọn cho Game',
                ]);
           }
//
          if($events->save()){
              if($events->giftcode_type == 1){
                  if($server[0] == 0){
                      $server_all = MerchantAppConfig::where('appid',$game_id)->where('status_server',1)->where('partner_id',0)->get();
                      foreach($server_all as $item){
                          $giftcode_event = new GiftCodeServer;
                          $giftcode_event->event_id = $events->id;
                          $giftcode_event->game_id = $game_id;
                          $giftcode_event->server_id = $item->serverid;
                          $giftcode_event->gift_code_type = $events->giftcode_type;
                          $giftcode_event->gift_code = $gift_code_text;
                          $giftcode_event->save();
                      }
                  }else{
                      for ($i = 0; $i < sizeof($server);$i++){
                          $giftcode_event = new GiftCodeServer;
                          $giftcode_event->event_id = $events->id;
                          $giftcode_event->game_id = $game_id;
                          $giftcode_event->server_id = $server[$i];
                          $giftcode_event->gift_code_type = $events->giftcode_type;
                          $giftcode_event->gift_code = $gift_code_text;
                          $giftcode_event->save();
                      }
                  }
              }
              if($events->giftcode_type == 2){
                  if($server[0] == 0){
                      $server_all = MerchantAppConfig::where('appid',$game_id)->where('status_server',1)->where('partner_id',0)->get();
                      foreach($server_all as $item){
                          for ($j = 0;$j < sizeof($gift_code);$j++){
                              $giftcode_event = new GiftCodeServer;
                              $giftcode_event->event_id = $events->id;
                              $giftcode_event->game_id = $game_id;
                              $giftcode_event->server_id = $item->serverid;
                              $giftcode_event->gift_code_type = $events->giftcode_type;
                              $giftcode_event->gift_code = $gift_code[$j];
                              $giftcode_event->save();
                          }
                      }
                  }else{
                      for ($i = 0; $i < sizeof($server);$i++){
                          for ($j = 0;$j < sizeof($gift_code);$j++){
                              $giftcode_event = new GiftCodeServer;
                              $giftcode_event->event_id = $events->id;
                              $giftcode_event->game_id = $game_id;
                              $giftcode_event->server_id = $server[$i];
                              $giftcode_event->gift_code_type = $events->giftcode_type;
                              $giftcode_event->gift_code = $gift_code[$j];
                              $giftcode_event->save();
                          }
                      }
                  }
              }
              return redirect()->route('list.event');
          }
       }

    }

    public  function DeleteEvent(Request $req){
            $id_events = $req->id_events;
            $id_events_array = explode(",",$id_events);
            if(sizeof($id_events_array)){
                foreach ($id_events_array as $id){
                    Event_game::where('events.id',$id)->delete();
                    GiftCodeServer::where('gift_game_servers.event_id','=',$id)->delete();
                }
                echo "true";
            }else{
                echo "false";
            }
    }

}
