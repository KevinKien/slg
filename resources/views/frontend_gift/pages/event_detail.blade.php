@extends('frontend_gift.layout')
@section('title')
    Chi tiết Event

@endsection
@section('css_custum')
    <link rel="stylesheet" href="{!! asset('frontend_gift/dist/css/bootstrap-select.css') !!}">
    <style>
        .list-inline  li  a{
            font-size: 15px;
        }
        .list-inline > .active{
            color: #e59403;
        }
        .breadcrumb{
            margin-bottom: 0px;
        }
        .games-play {
            display: inline-flex;
            border: 1px solid #00608D;
            color: #00608D;
            border-radius: 5px;
            text-align: right;
            text-transform: uppercase;
            font-size: 15px;
            margin-bottom: 10px;
            padding: 6px 6px;
            float: left;
            font-weight: Roboto;
            font-family: 'Roboto Condensed', sans-serif;
        }
        .games-play:hover {
            border: 1px solid #e59403;
            color: #fff;
            background:#e59403 ;
        }
        #rating span{
            color: #e59403;
            font-size: 16px;
        }
        .game-col{
            border-right: 1px solid #E59403;
        }
        .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
             width: 150px;
        }
        .btn-default{
            padding: 4px 10px!important;
            background: #fff!important;
            color: #E59403!important;
            border: 1px solid #E59403!important;
        }
        table tr td{
            padding:5px 0px 5px 10px;
        }
        .gift-span{
            padding: 37px;
        }
        .gift-span div{
            border: 1px solid #E59403;
            text-align: center;
            border-radius: 4%;
            color:#FFF;
            font-weight: 700;
            background:#E59403 ;

        }
        .is{
            text-transform: uppercase;
            font-size: 12px;
            background: #E59403;
            color: #fff;
            padding: 3px 6px 2px 3px;
            border-radius: 7%;
            text-shadow: 0 -1px 0 #fff,
            0 1px 0 #2e2e2e,
            0 22px 30px rgba(0, 0, 0, 0.9);;
        }
        .check-gift{
            padding:30px 0 20px 0;
        }
        span.header-main{
            font-size: 1.0em;
        }
        .fanpage li{
            margin-bottom: 10px;
        }
        .nav-tabs > li.active{
            border-bottom: 3px solid #E59403;
        }
        .like-fp{
            display: none;
        }
        @media (min-width: 768px){
            .modal-dialog {
                width: 450px !important;
                margin: 140px auto !important;
            }
        }
        @media(min-width: 768px){
            .diag{
                width: 730px !important;
            }
        }
        .note1{
            color: #E59403;
            font-size: 14px;
        }
        #history_code{
            border:1px solid #E59403;
            color: #E59403;
        }
        #history_code:hover{
            background: #E59403;
            color: #fff;
        }
        .col-md-3{
            margin-right: 0px;
            padding-left: 7px;
            margin-bottom: 20px;

        }

    </style>
@endsection
@section('content')
    <div class="container">
        <section class="breadcrumb " style="margin-top: 15px; ">
            <div class="container">
                <ul class="clearfix list-inline " style="" >
                    <li class=""><a href="">Home</a></li>
                    <li class="active"><a href=""> Code tân thủ</a></li>
                </ul>
            </div>
        </section>
        <!--Side Col-->
        <div class="col-md-5 game-col">
            <div class="side-container">
                <div class="sd-cnt">
                    <a href="{!! $game->url_homepage !!}"> <img src="{!! asset($event->image) !!}" class="img-game"/></a>
                    <h3>Thông tin về {!! $game->name !!}</h3>
                </div>
                <div class="sd-cnt">
                    <div style="margin-top: 5px ;">
                        <img class="img-responsive" style="float: left;margin-right: 10px;border-radius: 12%" src="https://placeimg.com/70/70/any" alt="">
                        <p><strong>Thể loại: </strong>Nhập vai</p>
                        <p><strong>Lượt chơi: </strong>{!! $game->user_num !!}</p>
                        <p id="rating"><strong>Đánh giá: </strong><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </p>
                    </div>
                    <a class="games-play" href="#">Play Game</a>
                    <div class="clearfix"></div>
                    <span class="game-des">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </span>
                </div>
            </div>
        </div>
        <!--End Side Col-->
        <!--Main Col-->
        <div class="col-md-7">
            <div class="news-block">
                <div class="coltainer">
                    <span class="header-main"><i class="fa fa-gamepad" style="color: #e59403"></i> {!! $game->name !!}</span>
                    <div class="row">
                        <div class=" main-news">
                            <table>
                                <tr>
                                    <td><strong>Loại code: </strong></td>
                                    <td><i class="is">
                                        @if($event->giftcode_type == 1)
                                            Tân Thủ
                                        @endif
                                        @if($event->giftcode_type == 2)
                                            User
                                        @endif
                                        </i></td>
                                </tr>
                                <tr>
                                    <input type="hidden" id="event" value="{!! $game->event_id !!}">
                                    <input type="hidden" id="game" value="{!! $game->game_id !!}">
                                    <input type="hidden" id="code_type" value="{!! $game->gift_code_type !!}">
                                    <td><strong>Thời hạn: </strong></td>
                                    <td>Từ {!! date('d-m-Y H:i:s',strtotime($event->time_min)) !!} đến {!! date('d-m-Y H:i:s',strtotime($event->time_max)) !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Server: </strong></td>
                                    <td>
                                        <select class="selectpicker" data-live-search="true" id="checked">
                                            <option disabled selected style="display: none" value="0">Chọn Server</option>
                                            @if(sizeof($server))
                                                @foreach($server as $item)
                                                    @foreach($user_game_server as $item2)
                                                        @if($item->serverid == $item2->server_id)
                                                            <option data-tokens="{!! $item->servername !!}" data-game="{!! $item->appid !!}" value="{!! $item->serverid !!}">{!! $item->servername !!}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>!!!</td>
                                    <td>Danh sách trên chỉ có những server bạn đã từng chơi </td>
                                </tr>
                                @if($event->giftcode_type == 2)
                                <tr>
                                    <td><strong>Còn lại: </strong></td>
                                    <td id="total" style="font-size: 16px;font-weight: 600">…</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="news-block" style="border-bottom: 1px solid #E59403;padding-bottom: 20px;">
                    <span class="header-main"> Điều kiện tham gia nhận Gift code</span>
                    @if(Cache::has('error_like'.Auth::id()))
                        <p style="color: #CD2A0E;font-weight: 700;font-size: 13px;padding-left: 10px;">Bạn chưa Like FanPage . Bạn hãy kiểm tra các bước của mình</p>
                    @endif
                    @if(isset($event->link_share))
                        @if(Cache::has('error_share'.Auth::id()))
                            <p id="succe" style="color: #CD2A0E;font-weight: 700;font-size: 13px;padding-left: 10px;">Bạn chưa Share Link. Bạn hãy kiểm tra các bước của mình</p>
                        @endif
                    @endif
                        <p class="note1">B1 . Like FanPage FaceBook</p>
                        <div class="fb-page "
                             data-href="https://www.facebook.com/OnePieceOnlineSLG"
                             data-width="350"
                             data-hide-cover="false"
                             data-show-facepile="true" >
                        </div>
                    @if(isset($event->link_share))
                        <p class="note1">B2 . Share link :</p>
                        <a href="#" onclick="shareOnFacebook()">{!! $event->link_share !!}
                    @endif
                </div>
                <div class="check-gift" style="text-align: center">
                    @if(Cache::has('gift-code_'.Auth::id().'_'.$event->id.'_'.$game->game_id))
                        <p id="gift">Bạn đã đạt đủ điều kiện để tham gia nhận gift code : Hãy chọn server rồi nhận code</p>
                        <a href="#" id="d_sm" class="btn btn-success ">Nhận Code</a>
                    @else
                        <a href="{!! route('loginfb',['id'=>$event->id]) !!}" class="btn btn-primary">Kiểm Tra</a>
                    @endif
                        <a href="#" id="history_code" class="btn ">Lịch sử nhận Code</a>
                </div>
                <div class="Information">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Thông Tin</a></li>
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Hướng Dẫn</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home"><img class="img-responsive" style="float: left;margin-right: 10px;" src="{!! $event->link_information !!}" alt=""></div>
                        <div role="tabpanel" class="tab-pane" id="profile"><img class="img-responsive" style="float: left;margin-right: 10px;" src="https://placeimg.com/615/700/any" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Main Col-->

       <div id="lienquan">
           <div class="container">
               <div class="col-md-12">
                   <div class="news-block">
                       <div class="coltainer">
                           <span class="header-main"><i class="fa fa-gamepad" style="color: #e59403"></i> Các sự kiện liên quan</span>
                           <div class="container">
                               <div class="row">
                                    <?php $date = date('Y-m-d H:i:s'); ?>
                                    @if(sizeof($relation_event) > 0)
                                        @foreach($relation_event as $item )
                                           <?php  $game = App\Models\MerchantApp::where('id',$item->game_id)->first();
                                           $img = json_decode($game->images,true); ?>
                                           @if( strtotime($date) >= strtotime($item->time_min) && strtotime($date) <= strtotime($item->time_max))
                                               <div class="col-md-3">
                                                   <a href="{!! route('event.detail',['id'=>$item->event_id]) !!}"><img class="img-responsive" src="{!! $item->image !!}" alt=""></a>
                                                   <div style="margin-top: 5px ;">
                                                       <img class="img-responsive" style="float: left;margin-right: 10px;" src="{!! $img['thumb'] !!}" alt="">
                                                       <h4><a href="{!! route('event.detail',['id'=>$item->event_id]) !!}">{!! $item->name !!}</a></h4>
                                                       <span>{!! $game->name !!}</span>
                                                   </div>
                                                   <div class="clearfix"></div>
                                               </div>
                                            @endif
                                        @endforeach
                                    @endif
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>

    </div>
    {{-- popup error --}}
    <div id="model1" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #E59403;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 style="color: #fff" class="modal-title">Thông báo !!!!!</h4>
                </div>
                <div class="modal-body">
                    <p style="color: #E59403;font-size: 15px;" id="error">1. Bạn hãy chọn server trước khi nhận gift code</p>
                </div>
                <div class="modal-footer" style="padding: 10px 20px 10px 0px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- popup history--}}
    <div id="model2" class="modal fade">
        <div class="modal-dialog diag" >
            <div class="modal-content">
                <div class="modal-header" style="background: #E59403;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 style="color: #fff" class="modal-title">Lịch sử tài khoản : {!! Auth::user()->name !!}</h4>
                </div>
                <div class="modal-body">
                    <?php $list_code_user = App\Models\HistoryEvent::where('user_id',Auth::id())->get(); ?>
                    @if(sizeof($list_code_user) > 0)
                        <table class="table table-hover">
                            <thead >
                            <tr>
                                <th>Game</th>
                                <th>Server</th>
                                <th>Loại Code</th>
                                <th>Code</th>
                                <th>Ngày Nhận</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list_code_user as $item)
                                <?php
                                    $game = App\Models\MerchantApp::where('id',$item->game_id)->first();
                                    $server = App\Models\MerchantAppConfig::where('serverid',$item->server_id)->where('partner_id',0)->first();
                                ?>
                                <tr>
                                    <td>{!! $game->name !!}</td>
                                    <td>{!! $server->servername !!}</td>
                                    <td>@if($item->gift_code_type == 1)
                                            Code Tân Thủ
                                        @else
                                            Code User
                                        @endif
                                    </td>
                                    <td>{!! $item->gift_code !!}</td>
                                    <td >{!! date('d-m-Y',strtotime($item->updated_at)) !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                            <p style="color: #E59403;font-size: 15px;" id="error">Bạn chưa tham gia nhận gift code nào !</p>
                    @endif
                </div>
                <div class="modal-footer" style="padding: 10px 20px 10px 0px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
@section('js_custum')



    <script src="{!! asset('frontend_gift/dist/js/bootstrap-select.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('frontend_gift/js/ev-detail.js') !!}"></script>
    <script>
       @if(isset($event->link_share))
       // share
       window.fbAsyncInit = function() {
           FB.init({
               appId      : '748333908644144',
               xfbml      : true,
               version    : 'v2.5'
           });
       };
       (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/en_US/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));

       function shareOnFacebook() {
           FB.ui(
               {
                   method        : 'feed',
                   display       : 'iframe',
                   name          : '{!! $event->name !!}',
                   link          : '{!! $event->link_share !!}',
                   picture       : '{!! asset($event->image) !!}',
                   description   : '{!! $event->description !!}',
                   access_token  : 'user access token'
               },
               function(response) {
                   if (response && response.post_id) {
                       $.ajax({
                           headers: {
                               'X-CSRF-TOKEN': '{{ csrf_token() }}'
                           },
                           type:"post",
                           url:"{{route('share.event')}}",
                           data:{},
                           success:function(data){
                                if(data.status == true){
                                    $('#succe').remove();
                                }
                           },
                           cache:false,
                           dataType: 'json'
                       });
                   }else {

                   }
               }
           );
       }
       @endif
    </script>

@endsection