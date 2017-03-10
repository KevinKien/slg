@extends('game.onepiece.zing.layout')

@section('content')
    
    <div class="main_con">
        <div class="new_sv">            
            @foreach($servers as $server)
                @if ($server['is_new'] == 1)
                    @if (!Session::has('zing_user'))
                        <a href="#"onclick="window.top.location.href = '{{ $zing_url . '?_srvid=' . $server['serverid'] . $src }}'">
                            {{ $server['servername'] }}                         
                        </a>
                    @else
                        <a href="{{ route('onepiece-zing.play') . '?_srvid=' . $server['serverid'] }}">
                            {{ $server['servername'] }}
                        </a>
                    @endif
                @endif
            @endforeach   
            @if(isset($recent_server_id))
                <a href="{{ route('onepiece-zing.play') . '?_srvid=' . $recent_server_id }}">ONE PIECE Z{{ $recent_server_id }}</a>
            @endif
        </div>
        <div class="fb_button">
            <ul>                
                @if (Session::has('zing_user'))
                    <li><a href="{{ route('onepiece-zing.payment')}}">NẠP TIỀN</a> </li>                   
                @else
                    <li><a href="javascript:login();">NẠP TIỀN</a> </li>                    
                @endif                
                <li><a href="{{ route('onepiece-zing.top')}}">TOP GAME</a> </li>
                <li><a href="#">FANPAGE</a> </li>
            </ul>
            <div class="user_id">
                <p>Xin chào,     </p>
                <a href="#">{{ $name or 'bạn chưa đăng nhập' }}</a>
            </div>
        </div>
        <div class="main_content">
            <div class="slider_news">
                <div><img src="{{ asset('/game/onepiece/zing/main/img/mockup_03.jpg') }}"></div>
                <div><img src="{{ asset('/game/onepiece/zing/main/img/mockup_02.jpg') }}"></div>
                <div><img src="{{ asset('/game/onepiece/zing/main/img/mockup_03.jpg') }}"></div>
            </div>
            @if (!empty($servers))
            <?php $chunks = array_chunk($servers, 12) ?>
            <div class="server_list">
                <ul class="idTabs">
                    @foreach($chunks as $i => $servers_block)
                        <?php $i++ ?>
                        <li><a href="#tab{{ $i }}">{{ ($i * 12) - 11 }} - {{ $i * 12  }}</a></li>
                    @endforeach                   
                </ul>
                @foreach($chunks as $i => $servers_block)
                    <?php $i++ ?>                          
                    <div id="tab{{ $i }}">                        
                        @foreach($servers_block as $server)                            
                            <div class="row1">
                            @if (!Session::has('zing_user'))
                                <a href="#" class="may_chu" onclick="window.top.location.href = '{{ $zing_url . '?_srvid=' . $server['serverid'] . $src }}'">
                                    <img src="{{ asset('/game/onepiece/zing/main/img/servericon_11.png') }}">
                                    {{ $server['servername'] }}
                                </a>
                            @else
                                <a href="{{ route('onepiece-zing.play') . '?_srvid=' . $server['serverid'] }}" class="may_chu">
                                    <img src="{{ asset('/game/onepiece/zing/main/img/servericon_11.png') }}">
                                    {{ $server['servername'] }}                                    
                                </a>
                            @endif
                            </div>                            
                        @endforeach      
                        
                    </div>                                            
                @endforeach
                
            </div>
            @endif
        </div>
        <div class="bot_menu">
            <ul>
                @if (Session::has('zing_user'))
                    <li><a href="{{ route('onepiece-zing.payment')}}">NẠP TIỀN</a> </li>
                @else                
                    <li><a href="javascript:login();">NẠP TIỀN</a> </li>                                       
                @endif     
                <li><a href="http://me.zing.vn/megift/d/code/38/124">GIFTCODE</a> </li>
                <li><a href="#">TIN TỨC</a> </li>
                <li><a href="{{ route('onepiece-zing.top')}}">TOP GAME</a> </li>
                <li><a href="#">LIÊN HỆ</a> </li>

            </ul>
        </div>

    </div>    
    <script type="text/javascript">
        $(document).ready(function(){
            $('.slider_news').slick({
                autoplay: true,
                autoplaySpeed: 3000,
                dots: true,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                prevArrow: false,
                nextArrow: false
            });
        });
    </script>
@endsection

@section('additional-footer')
    <script>
        $('ul.idTabs').idTabs();
    </script>
@endsection