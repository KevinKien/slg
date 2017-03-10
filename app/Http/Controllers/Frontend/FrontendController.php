<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GiftCodeServer;
use App\Models\Event_game;
use App\Models\MerchantApp;
use App\Models\HistoryEvent;
use App\Models\UserGame;
use App\Models\UserGameServer;
use DB,Auth,Cache;
use App\Models\MerchantAppConfig;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Home()
    {
        $event_new = Event_game::select('events.*','gift_game_servers.*')
            ->leftjoin('gift_game_servers','gift_game_servers.event_id','=','events.id')
            ->where('events.status',1)->where('events.giftcode_type',1)
            ->groupby('events.id')
            ->get();
        $event_user = Event_game::select('events.*','gift_game_servers.*')
            ->leftjoin('gift_game_servers','gift_game_servers.event_id','=','events.id')
            ->where('events.status',1)->where('events.giftcode_type',2)
            ->get();
        //->groupby('gift_game_servers.game_id')
        return view("frontend_gift.pages.home",['event_new'=>$event_new,'event_user'=>$event_user]);
    }
    // event detail
    public function EventDetail(Request $req,$id){
        $event = Event_game::find($id);
        if(isset($event)){
            $game = GiftCodeServer::select('gift_game_servers.*','merchant_app.*')
                ->rightjoin('merchant_app','merchant_app.id','=','gift_game_servers.game_id')
                ->where('gift_game_servers.event_id',$event->id)->where('merchant_app.status',1)
                ->first();
            $server_id = GiftCodeServer::where('event_id','=',$event->id)
                ->where('game_id',$game->game_id)
                ->groupby('server_id')
                ->get();
            $in = array();
            foreach ($server_id as $value){
                array_push($in,$value->server_id);
            }
            $server = MerchantAppConfig::wherein('serverid',$in)->where('appid',$game->game_id)->where('status_server',1)->where('partner_id',0)->get();
            $user_game_server = UserGame::ListGameServer(Auth::id(),$game->game_id);
            $relation_event = Event_game::select('events.*','gift_game_servers.*')
                ->leftjoin('gift_game_servers','gift_game_servers.event_id','=','events.id')
                ->where('events.status',1)->where('events.giftcode_type',$event->giftcode_type)
                ->where('events.id','<>',$event->id)
                ->groupby('events.id')
                ->get();
            return view("frontend_gift.pages.event_detail",['event'=>$event,'game'=>$game,'server'=>$server,'user_game_server'=>$user_game_server,'relation_event'=>$relation_event]);
        }else{
            return redirect()->route('get.gift.code');
        }
    }

    public function ChangeServerDetail(Request $request){
        $event_id = $request->event;
        $game_id = $request->game;
        $server_id = $request->server_id;
        $code_type = $request->code_type;
        $events = Event_game::EventCodeTypePublic($event_id,$code_type,1);
        if(isset($events)){
            $game_server = MerchantAppConfig::where('appid',$game_id)->where('serverid',$server_id)->where('status_server',1)->where('partner_id',0)->first();
            if(isset($game_server)){
                $sizeof_gift = GiftCodeServer::where('event_id',$events->id)
                    ->where('game_id',$game_server->appid)
                    ->where('server_id',$game_server->serverid)
                    ->where('gift_code_type',$events->giftcode_type)
                    ->where('status',0)
                    ->get();
                return json_encode((array('status'=>true,'count'=>sizeof($sizeof_gift))));
            }else{
                return json_encode(array('status'=>false));
            }
        }else{
            return json_encode(array('status'=>false));
        }
    }

    public function RingGiftCode(Request $request){
        $id_event = $request->event;
        $id_game = $request->game;
        $id_server = $request->server_id;
        $code_type = $request->code_type;
        $event = Event_game::EventCodeTypePublic($id_event,$code_type,1);
        $user_game_server = UserGame::select('user_game.*','user_game_server.*')
            ->leftjoin('user_game_server','user_game_server.ugid','=','user_game.id')
            ->where('user_game.uid',Auth::id())
            ->where('user_game.app_id',$id_game)
            ->where('user_game.partner_id',0)
            ->where('user_game_server.server_id',$id_server)
            ->first();
        if(isset($user_game_server)){
            if(isset($event)){
                $game_server = MerchantAppConfig::where('appid',$id_game)->where('serverid',$id_server)->where('status_server',1)->where('partner_id',0)->first();
                if(isset($game_server)){
                    $date = date('Y-m-d H:i:s');
                    if(strtotime($date)>= strtotime($event->time_min) && strtotime($date)<=strtotime($event->time_max)){
                        //check lick su nhan code
                        $history_gift = HistoryEvent::where('user_id',Auth::id())->where('event_id',$event->id)->where('game_id',$game_server->appid)->where('server_id',$game_server->serverid)->where('gift_code_type',$event->giftcode_type)->first();
                        if(!$history_gift){
                            // k ! thì phát code
                            if ($event->giftcode_type == 1){
                                $code = GiftCodeServer::where('event_id',$event->id)->where('game_id',$game_server->appid)->where('server_id',$game_server->serverid)->where('gift_code_type',$event->giftcode_type)->first();
                                $history = new HistoryEvent;
                                $history->user_id = Auth::id();
                                $history->event_id = $event->id;
                                $history->game_id = $game_server->appid;
                                $history->server_id = $game_server->serverid;
                                $history->gift_code_type = $event->giftcode_type;
                                $history->gift_code = $code->gift_code;
                                if($history->save()){
                                    return json_encode(array('status'=>true,'message'=>'<p>'.'Mã Code: '.'</p>'.'<p style="border:1px solid #EAAE43;border-radius:4%;padding:6px;color:#EAAE43;margin:10px 66px;">'.$history->gift_code.'</p>'.'<p>'."Cảm ơn bạn đã tham gia sự kiện này"."</p>",'suces'=>1));
                                }
                            }
                            if ($event->giftcode_type == 2){
                                $gift_code =GiftCodeServer::TakeCode($event->id,$game_server->appid,$game_server->serverid,$event->giftcode_type,0);
                                if(sizeof($gift_code) > 0){
                                    // nếu còn code thì phát code
                                    $code = GiftCodeServer::where('event_id',$event->id)->where('game_id',$game_server->appid)->where('server_id',$game_server->serverid)->where('gift_code_type',$event->giftcode_type)->where('status',0)->first();
                                    $code->status = 1;
                                    if($code->save()){
                                        $history = new HistoryEvent;
                                        $history->user_id = Auth::id();
                                        $history->event_id = $event->id;
                                        $history->game_id = $game_server->appid;
                                        $history->server_id = $game_server->serverid;
                                        $history->gift_code_type = $event->giftcode_type;
                                        $history->gift_code = $code->gift_code;
                                        if($history->save()){
                                            return json_encode(array('status'=>true,'message'=>'<p>'.'Mã Code: '.'</p>'.'<p style="border:1px solid #EAAE43;border-radius:4%;padding:6px;color:#EAAE43;margin:10px 66px;">'.$history->gift_code.'</p>'.'<p>'."Cảm ơn bạn đã tham gia sự kiện này"."</p>",'suces'=>0));
                                        }
                                    }
                                }else{
                                    // nếu hết code thì xuất thông báo
                                    return json_encode(array('status'=>false,'message'=>'Xin lỗi bạn! Loại Gift code này đã phát hết . Bạn hãy đợi events sau','error'=>3));
                                }
                            }
                        }else{
                            // nếu ! thì xuất thông báo
                            return json_encode(array('status'=>false,'message'=>'Xin lỗi bạn! Bạn đã nhận Gift Code này rồi','error'=>1));
                        }
                    }else{
                        return json_encode(array('status'=>false,'message'=>'Xin lỗi bạn! Đã quá thời gian diễn ra sự kiện','error'=>2));
                    }
                }else{
                    return json_encode(array('status'=>false,'error'=>0));
                }
            }else{
                return json_encode(array('status'=>false,'error'=>0));
            }
        }else{
            return json_encode(array('status'=>false,'error'=>0));
        }
    }

    public function ShareEvent(Request $request){
        Cache::put('share_event_'.Auth::id(),'',30);
        return json_encode(array('status'=>true));
    }

}
