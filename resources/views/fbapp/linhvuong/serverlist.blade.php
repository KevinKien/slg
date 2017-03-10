@extends('fbapp.linhvuong.layout_serverlist')

@section('content')
<div class="wrapper">
    <div class="container top_menu">
        <a href="#" class="logo"></a>

        <div class="user">
            <span>Xin chào, <b>Thành chủ vip</b></span>
        </div>
        <div class="menu">
            <span class="nav"><a target="_blank" href="http://linhvuong.slg.vn/home/">Trang chủ</a>  |  <a target="_blank" href="http://diendan.slg.vn/">Group</a>  |  <a target="_blank" href="https://www.facebook.com/linhvuongtruyenky">Fanpage</a></span>
					<span class="func">
						<a href="#" class="card"></a>
						<div class="fb-like" data-href="https://www.facebook.com/linhvuongtruyenky/"
                             data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
                        <a href="#" onclick="invite();" class="invite">Invite</a>
					</span>
        </div>
    </div>

    <div class="container server_cnt">
        <a href="#" class="giftcode"></a>
        <hr>
        <div class="server_side">
            <span>Máy chủ mới chơi</span>
            <a href="#">LV - S01</a>
        </div>
        <div class="server_side svnew">
            <span>Máy chủ mới chơi</span>
            <a href="#">LV - S01<span></span></a>
            <!--a href="#" class="top"></a-->
        </div>

        <div class="news">
            <!--Slide-->
            <div id="slide">
                <div id="banner">
                    <a href="#"><img src="//app.slg.vn/game/linhvuong/images/background.jpg" border="0"></a>
                    <a href="#"><img src="//app.slg.vn/game/linhvuong/images/background.jpg" border="0"></a>
                    <a href="#"><img src="//app.slg.vn/game/linhvuong/images/background.jpg" border="0"></a>
                    <a href="#"><img src="//app.slg.vn/game/linhvuong/images/background.jpg" border="0"></a>
                    <a href="#"><img src="//app.slg.vn/game/linhvuong/images/background.jpg" border="0"></a>
                </div>
                <div id="btn"></div>
            </div>
            <!--Slide-->
        </div>

        <div class="server_group">
            <ul class="idTabs">
                <li><a href="#1st">01-12</a></li>
                <li><a href="#2nd">13-22</a></li>
            </ul>
            <div class="tabdetail">
                <div id="1st">
                    <a target="_blank" href="http://dev.linhvuong.slg.vn/home/slg_login?server=s202">LV - S01</a>
                    <a target="_blank" href="http://dev.linhvuong.slg.vn/home/slg_login?server=S06">LV - S02<span></span></a>
<!--                    <a href="#">LV - S03</a>
                    <a href="#">LV - S04</a>
                    <a href="#">LV - S05</a>
                    <a href="http://linhvuong.slg.vn/home/vao-game/S06/113">LV - S06<span></span></a>
                    <a href="#">LV - S07</a>
                    <a href="#">LV - S08<span></span></a>-->
                </div>
<!--                <div id="2nd">2</div>-->
            </div>
        </div>
    </div>
    <div id="slider2">
        <iframe style="width: 760px; height: 250px" src="https://app.slg.vn/games?fb=linhvuongtruyenky"></iframe>
    </div>
    <div id="slider1">
        <a class="buttons prev" href="#">&#60;</a>
        <div class="viewport">
            <ul class="overview">
                <li>
                    <a href="#">
                        <img src="//app.slg.vn/game/linhvuong/images/app/1.gif" alt="App 1"/>
                        <span>
                            <b>Tên game 01</b>
                            <p>Phiên bản mới nhất của Anh hùng Tam Quốc</p>
                            <p><img src="//app.slg.vn/game/linhvuong/images/user.png"/>136.543</p>
                        </span>
                    </a>
                    <a href="#">
                        <img src="//app.slg.vn/game/linhvuong/images/app/2.gif" alt="App 1"/>
                        <span>
                            <b>Tên game 02</b>
                            <p>Phiên bản mới nhất của Anh hùng Tam Quốc</p>
                            <p><img src="//app.slg.vn/game/linhvuong/images/user.png"/>136.543</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="//app.slg.vn/game/linhvuong/images/app/3.gif" alt="App 1"/>
                        <span>
                            <b>Tên game 03</b>
                            <p>Phiên bản mới nhất của Anh hùng Tam Quốc</p>
                            <p><img src="//app.slg.vn/game/linhvuong/images/user.png"/>136.543</p>
                        </span>
                    </a>
                    <a href="#">
                        <img src="//app.slg.vn/game/linhvuong/images/app/4.gif" alt="App 1"/>
                        <span>
                            <b>Tên game 04</b>
                            <p>Phiên bản mới nhất của Anh hùng Tam Quốc</p>
                            <p><img src="//app.slg.vn/game/linhvuong/images/user.png"/>136.543</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="//app.slg.vn/game/linhvuong/images/app/5.gif" alt="App 1"/>
                        <span>
                            <b>Tên game 05</b>
                            <p>Phiên bản mới nhất của Anh hùng Tam Quốc</p>
                            <p><img src="//app.slg.vn/game/linhvuong/images/user.png"/>136.543</p>
                        </span>
                    </a>
                    <a href="#">
                        <img src="//app.slg.vn/game/linhvuong/images/app/6.gif" alt="App 1"/>
                        <span>
                            <b>Tên game 06</b>
                            <p>Phiên bản mới nhất của Anh hùng Tam Quốc</p>
                            <p><img src="//app.slg.vn/game/linhvuong/images/user.png"/>136.543</p>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <a class="buttons next" href="#">&#62;</a>
    </div>
    

    <div class="footer">
        Bản quyền thuộc về Boat6 - Cty CP DV trực tuyến Vĩnh Xuân độc quyền phát hành tại Việt Nam<br>
        Hotline: 043.5.380.202 - Email: hotro.op@slg.vn<br>
        Điều khoản - Chơi game nhiều gây hại cho sức khỏe<br>
    </div>
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
</body>
@endsection