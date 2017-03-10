@extends('fbapp.onepiece.layout_serverlist')

@section('content')
<div class="wrapper">
    <div class="container top_menu">
        <a href="#" class="logo"></a>
        <div class="user">
            @if(is_object(Auth::user()) &&  Auth::user()->name )
            <span>Xin chào, <b>{{ !empty(Auth::user()->fullname) ? Auth::user()->fullname : Auth::user()->name }}</b></span>
            @else
            <span>Xin chào, <b>Guest</b></span>
            @endif
        </div>
        <div class="menu">           
            
            <span class="nav"><a target="_blank" href="http://op.slg.vn">Trang chủ</a>  |  <a target="_blank" href="http://diendan.slg.vn/forum.php">Diễn đàn</a>  |  <a target="_blank" href="https://www.facebook.com/OnePieceOnlineSLG/">Fanpage</a></span>
            <span class="func">
                <a href="http://pay.slg.vn/topcoin/one-piece-online" target="_blank" class="card"></a>
            <div class="fb-like" data-href="https://www.facebook.com/OnePieceOnlineSLG/" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
            <a href="#" onclick="invite();" class="invite">Invite</a>
            </span>
        </div>
    </div>

    <div class="container server_cnt">
        <a href="http://op.slg.vn/home/#giftcode" target="_blank" class="giftcode"></a>
        <hr>
        <div class="server_side">
            <span>Máy chủ mới chơi</span>
            @if(isset($server_user))
                <a target="_blank" href="http://op.slg.vn/home/vao_game/{{$server_user}}">OnePiece S{{$server_user}}</a>
            @endif
            @if(!isset($server_user))
                @foreach($servers as $key => $server)
                    @if($server['is_new'] == 1)
                        <a target="_blank" href="http://op.slg.vn/home/vao_game/{{$server['serverid']}}">{{$server['servername']}}</a>
                    @endif
                @endforeach
            @endif
            <!--<a href="#">One Piece S01</a>-->
        </div>
        <div class="server_side svnew">
            <span>Máy chủ mới nhất</span>
            @if(is_array($servers))
                @foreach($servers as $key => $server)
                    @if($server['is_new'] == 1)
                    <a target="_blank" href="http://op.slg.vn/home/vao_game/{{$server['serverid']}}">{{$server['servername']}}</a>
                    @endif
                @endforeach
            @endif
                  
            <a href="http://op.slg.vn/home/top_game" target="_blank" class="top"></a>
        </div>

        <div class="news">
            <!--Slide-->
            <div id="slide">
                <div id="banner">
                    <a target="_blank" href="https://www.facebook.com/notes/one-piece-online/hot-s%E1%BB%B1-ki%E1%BB%87n-qu%C3%A0-t%E1%BA%B7ng-n%E1%BA%A1p-t%C3%ADch-l%C5%A9y/1753027544928699"><img src="https://app.slg.vn/fbapp/onepiece/images/1.jpg" border="0"></a>
                    <a target="_blank" href="https://www.facebook.com/notes/one-piece-online/hot-s%E1%BB%B1-ki%E1%BB%87n-n%E1%BA%A1p-ti%E1%BB%81n-b%E1%BB%91c-th%C6%B0%E1%BB%9Fng/1753006598264127"><img src="https://app.slg.vn/fbapp/onepiece/images/2.jpg" border="0"></a>
                    <a target="_blank" href="https://www.facebook.com/notes/one-piece-online/event-ti%C3%AAu-ti%E1%BB%81n-ho%C3%A0n-tr%E1%BA%A3-50/1753005471597573"><img src="https://app.slg.vn/fbapp/onepiece/images/3.jpg" border="0"></a>
                    <a target="_blank" href="https://www.facebook.com/notes/one-piece-online/hot-s%E1%BB%B1-ki%E1%BB%87n-ph%E1%BA%A7n-th%C6%B0%E1%BB%9Fng-n%E1%BA%A1p-ti%E1%BB%81n/1753016888263098"><img src="https://app.slg.vn/fbapp/onepiece/images/4.jpg" border="0"></a>
                    <a target="_blank" href="https://www.facebook.com/notes/one-piece-online/hot-s%E1%BB%B1-ki%E1%BB%87n-qu%C3%A0-t%E1%BA%B7ng-n%E1%BA%A1p-1-l%E1%BA%A7n/1753019658262821"><img src="https://app.slg.vn/fbapp/onepiece/images/5.jpg" border="0"></a>
                    <a target="_blank" href="https://www.facebook.com/notes/one-piece-online/hot-s%E1%BB%B1-ki%E1%BB%87n-v%C3%B2ng-quay-may-m%E1%BA%AFn/1753025154928938"><img src="https://app.slg.vn/fbapp/onepiece/images/6.jpg" border="0"></a>
                </div>
                <div id="btn"></div>
            </div>
            <!--Slide-->
        </div>

        <div class="server_group">
            <ul class="idTabs">
                <!--<li><a href="#1st">01-08</a></li>
                <li><a href="#2nd">09-16</a></li>
                <li><a href="#3nd">17-24</a></li>
                <li><a href="#4nd">25-32</a></li>
                <li><a href="#5nd">33-40</a></li>
                <li><a href="#6nd">41-48</a></li>
                <li><a href="#7nd">49-56</a></li>
                <li><a href="#8nd">57-64</a></li>
                <li><a href="#9nd">65-72</a></li>
                <li><a href="#10nd">73-80</a></li>-->
                <li><a href="#11nd">81-88</a></li>
                <li><a href="#12nd">89-96</a></li>
                <li><a href="#13nd">97-104</a></li>
                
            </ul>
            <div class="tabdetail">
                
                <div id="11nd">
                    @if(is_array($servers))
                        @foreach($servers as $key => $server)
                            @if($server['serverid'] <= 88 && $server['serverid'] > 80)
                            <a target="_blank" href="http://op.slg.vn/home/vao_game/{{$server['serverid']}}">{{$server['servername']}}</a>
                            @endif
                        @endforeach
                    @endif                    
                </div>
                <div id="12nd">
                    @if(is_array($servers))
                        @foreach($servers as $key => $server)
                            @if($server['serverid'] <= 96 && $server['serverid'] > 88)
                            <a target="_blank" href="http://op.slg.vn/home/vao_game/{{$server['serverid']}}">{{$server['servername']}}</a>
                            @endif
                        @endforeach
                    @endif                    
                </div>
                <div id="13nd">
                    @if(is_array($servers))
                        @foreach($servers as $key => $server)
                            @if($server['serverid'] <= 104 && $server['serverid'] > 96)
                            <a target="_blank" href="http://op.slg.vn/home/vao_game/{{$server['serverid']}}">{{$server['servername']}}</a>
                            @endif
                        @endforeach
                    @endif                    
                </div>
            </div>
        </div>
    </div>
    <div id="slider2">
        <iframe style="width: 760px; height: 250px" src="https://app.slg.vn/games?fb=OnePieceOnlineSLG"></iframe>
    </div>
    <style>
        #slider2 {
            width: 778px;
            overflow: hidden;
            float: left;
            padding: 12px 0 0 14px;
            margin-left: 19px;
            border: solid 1px rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
    
</div>
</body>
@endsection