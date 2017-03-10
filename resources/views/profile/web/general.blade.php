
@extends('layout.newprofileweb.layout')
@section('css-current')
    <!-- Bootstrap 3.3.4 -->
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
@endsection
@section('htmlheader_title')
Thông tin đăng nhập
@endsection
@section('content')
<section id="ContentWrap">
<div class="box ">
    <div class="row">
        <div class="col-md-4" >
            @include('profile.web.profilebar')
        </div>
        <div class="col-md-8" >
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Thông tin đăng nhập</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table profiletable">
                        <tbody>
                            <tr >
                                <td class="labels">Tên đăng nhập</td>
                                <td><b>{{ Auth::user()->name }}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="labels">Email đăng nhập</td>
                                <td><b>{{ strlen( Auth::user()->email)>6?substr(Auth::user()->email,0,3).'*******'.substr(Auth::user()->email,-3,3):'(Chưa có thông tin)' }}</b></td>
                                <td></td>
                            </tr>
                            <tr >
                                <td class="labels">SĐT đăng nhập</td>
                                <td><b>{{ strlen( Auth::user()->phone)>6?substr(Auth::user()->phone,0,3).'*******'.substr(Auth::user()->phone,-3,3):'(Chưa có thông tin)'}}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="labels">Mật khẩu</td>
                                <td><b>*****************</b></td>
                                <td><a class="btn btn-primary" href="/profile/changepass">Đổi mật khẩu</a></td>
                            </tr>
                            
                            
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<div class="full-wraping">
     games list
    <div class="games-container">
        <div class="container">
            <div class="list-games" id="games">
                <div class="row">
                    <h2>SLG's GAMES</h2>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container center-block">
                            <img class="img-responsive center-block" src="images/g1.png">

                            <h3>TỨ HOÀNG ĐẠI CHIẾN</h3>

                            <p>1.000.000+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a href="#" class="choingay-button">CHƠI NGAY</a>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container">
                            <img class="img-responsive center-block" src="images/g2.png">

                            <h3>LINH VƯƠNG</h3>

                            <p>400.000+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a href="#" class="choingay-button">CHƠI NGAY</a>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container">
                            <img class="img-responsive center-block" src="images/g3.png">

                            <h3>MAGA HEROES</h3>

                            <p>300.000+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a href="#" class="choingay-button">CHƠI NGAY</a>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container">
                            <img class="img-responsive center-block" src="images/g4.png">

                            <h3>THIÊN LONG TRUYỀN KỲ</h3>

                            <p>100.000+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a href="#" class="choingay-button">CHƠI NGAY</a>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container">
                            <img class="img-responsive center-block" src="images/g5.png">

                            <h3>PHONG THẦN DIỄN NGHĨA</h3>

                            <p>400.000+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a href="#" class="choingay-button">CHƠI NGAY</a>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container">
                            <img class="img-responsive center-block" src="images/g6.png">

                            <h3>NGỘ KHÔNG RUỒI</h3>

                            <p>400.000+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a href="#" class="choingay-button">CHƠI NGAY</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
</section>
@endsection