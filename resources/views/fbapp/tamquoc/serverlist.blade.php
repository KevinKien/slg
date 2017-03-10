@extends('fbapp.tamquoc.layout_serverlist')

@section('content')
    <div class="main_con">
        <div class="new_sv">
            @if(is_array($servers))
                @foreach($servers as $key => $server)
                    @if($server['is_new'] == 1)
                        <a target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">{{$server['servername']}}</a>
                    @endif
                @endforeach
            @endif 
            
            @if(isset($server_user))
                @foreach($servers as $key => $server)
                    @if($server['serverid'] == $server_user)
                        <a target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">{{$server['servername']}}</a>
                    @endif
                @endforeach              
            @endif
            @if(!isset($server_user))
                @foreach($servers as $key => $server)
                    @if($server['is_new'] == 1)
                        <a target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">{{$server['servername']}}</a>
                    @endif
                @endforeach
            @endif           
        </div>
        <div class="fb_button">
            <ul>                              
                <div class="fb-like" data-href="https://www.facebook.com/TamQuocTruyenKySLG/" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true" ></div>                                
                <li><a href="#" onclick="invite();" class="invite">Invite</a> </li>
            </ul>
            <div class="user_id">
                <p>Xin chào, {{ !empty(Auth::user()->fullname) ? Auth::user()->fullname : Auth::user()->name }}</p>
            </div>
        </div>
        <div class="main_content">
            <div class="slider_news">
                <a href="http://tamquoctruyenky.vn/tin-tuc/bai-viet/hot-khai-mo-may-chu-moi-vi-thuy-10h00-3012" target="_blank">
                <div><img src="https://store-slg.cdn.vccloud.vn/TQTK/667%20-%20255%20Vi%20Thuy.png"></div></a>                
                <a href="http://tamquoctruyenky.vn/tin-tuc/bai-viet/hot-than-thu-chien-chuoi-su-kien-tuan-2-thang-12" target="_blank">
                <div><img src="https://store-slg.cdn.vccloud.vn/TQTK/than%20thu%20chien_667-255.png"></div></a>               
                <!--<div><img src="https://app.slg.vn/fbapp/tamquoc/img/mockup_02.jpg"></div>
                <div><img src="https://app.slg.vn/fbapp/tamquoc/img/mockup_03.jpg"></div>--> 
            </div>
            
            <div class="server_list">
                <ul class="idTabs">                
                    @if($count > 24)
                        <li><a href="#tab3">25 - 36</a></li>
                    @endif
                    @if($count > 12)
                        <li><a href="#tab2">13 - 24</a></li>
                    @endif
                    <li><a href="#tab1">1 - 12</a></li>
                </ul>
                <div id="tab1">
                    <div class="row1">                        
                        @if(is_array($servers))
                            @foreach($servers as $key => $server)
                                @if($server['serverid'] <= 6 && $server['serverid'] > 0)
                                <a class="may_chu" target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">
                                    <img src="https://app.slg.vn/fbapp/tamquoc/img/servericon_11.png">
                                    <span>{{$server['servername']}}</span>
                                </a>
                                @endif
                            @endforeach
                        @endif       
                    </div>
                    <div class="row1">
                        @if(is_array($servers))
                            @foreach($servers as $key => $server)
                                @if($server['serverid'] <= 12 && $server['serverid'] > 6)
                                <a class="may_chu" target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">
                                    <img src="https://app.slg.vn/fbapp/tamquoc/img/servericon_11.png">
                                    <span>{{$server['servername']}}</span>
                                </a>
                                @endif
                            @endforeach
                        @endif   
                    </div>
                </div>
                @if($count > 12)
                    <div id="tab2">
                        <div class="row1">                        
                            @if(is_array($servers))
                                @foreach($servers as $key => $server)
                                    @if($server['serverid'] <= 18 && $server['serverid'] > 12)
                                    <a class="may_chu" target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">
                                        <img src="https://app.slg.vn/fbapp/tamquoc/img/servericon_11.png">
                                        <span>{{$server['servername']}}</span>
                                    </a>
                                    @endif
                                @endforeach
                            @endif       
                        </div>
                        <div class="row1">
                            @if(is_array($servers))
                                @foreach($servers as $key => $server)
                                    @if($server['serverid'] <= 24 && $server['serverid'] > 18)
                                    <a class="may_chu" target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">
                                        <img src="https://app.slg.vn/fbapp/tamquoc/img/servericon_11.png">
                                        <span>{{$server['servername']}}</span>
                                    </a>
                                    @endif
                                @endforeach
                            @endif   
                        </div>
                    </div>
                @endif
                @if($count > 24)
                    <div id="tab3">
                        <div class="row1">                        
                            @if(is_array($servers))
                                @foreach($servers as $key => $server)
                                    @if($server['serverid'] <= 30 && $server['serverid'] > 24)
                                    <a class="may_chu" target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">
                                        <img src="https://app.slg.vn/fbapp/tamquoc/img/servericon_11.png">
                                        <span>{{$server['servername']}}</span>
                                    </a>
                                    @endif
                                @endforeach
                            @endif       
                        </div>
                        <div class="row1">
                            @if(is_array($servers))
                                @foreach($servers as $key => $server)
                                    @if($server['serverid'] <= 36 && $server['serverid'] > 30)
                                    <a class="may_chu" target="_blank" href="http://tamquoctruyenky.vn/play?server={{$server['serverid']}}">
                                        <img src="https://app.slg.vn/fbapp/tamquoc/img/servericon_11.png">
                                        <span>{{$server['servername']}}</span>
                                    </a>
                                    @endif
                                @endforeach
                            @endif   
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="bot_menu">
            <ul>
                <li><a target="_blank" href="http://pay.slg.vn/topupcash">NẠP TIỀN</a> </li>
                <li><a target="_blank" href="http://tamquoctruyenky.vn/tin-tuc/bai-viet/hot-giftcode-tan-thu">GIFTCODE</a> </li>
                <li><a target="_blank" href="http://diendan.slg.vn/room/4-tam-quoc-truyen-ky">DIỄN ĐÀN</a> </li>
                <li><a target="_blank" href="https://www.facebook.com/TamQuocTruyenKySLG/">FANPAGE</a> </li>
                <li><a target="_blank" href="http://tamquoctruyenky.vn/huong-dan/bai-viet/huong-dan-cam-nang-tan-thu">CẨM NANG</a> </li>
            </ul>
        </div>
        <footer>
             <p>
                Bản quyền thuộc Hainan Dynamic Vanguard Network Technology Co.,Ltd do Cty CP DV trực tuyến Vĩnh Xuân phân phối duy nhất tại Việt Nam<br>
                Trụ sở: Tầng 5 tòa nhà Vĩnh Xuân Số 39 Trần Quốc Toản, phường Trần Hưng Đạo, quận Hoàn Kiếm, thành phố Hà Nội<br>
                Email hỗ trợ : hotro@slg.vn - Điện thoại : 043.5.380.202 số máy lẻ 101+103<br>
                <!--Giấy phép cung cấp dịch vụ trò chơi G1 số 194/GP-BTTTT. Quyết định phê duyệt nội dung kịch bản số 2018/QĐ-BTTTT<br>
                Cơ quan cấp phép: Bộ Thông tin và Truyền thông<br>
                Chịu trách nhiệm nội dung: Hà Hồng Minh<br>-->
             </p>
            <img src="https://app.slg.vn/fbapp/tamquoc/img/logo_08.png">
        </footer>

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
