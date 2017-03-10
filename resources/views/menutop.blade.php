<!DOCTYPE HTML>
<html lang = "en">
<head>
    <meta charset="utf-8" />
    <title>menu</title>
    <link rel="stylesheet" type="text/css" href="//id.slg.vn/plugins/menu/css/reset.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="//id.slg.vn/plugins/menu/css/font-awesome.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="//id.slg.vn/plugins/menu/css/main-stylesheet.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="//id.slg.vn/plugins/menu/css/bjqs.css"/>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Oswald:300,400,700|Source+Sans+Pro:300,400,600,700&amp;subset=latin,latin-ext" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300,300italic&subset=latin,vietnamese' rel='stylesheet' type='text/css'>


    <script src="//id.slg.vn/plugins/menu/jscript/jquery-1.11.2.min.js"></script>
    <script src="//id.slg.vn/plugins/menu/jscript/bjqs-1.3.min.js"></script>

    <script class="secret-source">
        jQuery(document).ready(function($) {
            $('#banner-fade').bjqs({
                height      : 40,
                width       : 200,
                showcontrols : false,
                centercontrols : false,
                nexttext : 'Next',
                prevtext : 'Prev',
                showmarkers : false,
                centermarkers : false,
                animtype : 'slide',
                animduration : 200,
                animspeed : 3000,
            });
        });
    </script>
</head>
<body>
<div id="top-layer">
    <div id="header-top">
        <div class="wrapper">
            <ul class="right">
                <li><a href="#">Đăng nhập<small><i class="fa fa-user"></i></small></a></li>
                <li><a href="login.html">Đăng ký</a></li>
            </ul>
            <ul class="load-responsive" rel="Top menu">
                <li><a href="podcasts.html"><img src="images/top-sub-menu/logo.png"></a></li>
                <li><a href="login.html">Trang chủ</a></li>
                <li><a href="messages.html"><span class="">Games</span></a>
                    <ul class="sub-menu top-game-menu">
                        @foreach ($listgame as $row1 => $result)
                        <div class="webgame-topmenu">
                            @if($result->is_hot == 1 && $result->is_new == 0)
                                <div class="op-top-menu" style="background-image: url('{{$result->imagemenu}}');">
                                    <span>
                                        <a href="{{$result->url_homepage}}">{{$result->name}}</a>
                                    </span>
                                    <span class="submenugig">
                                        <img src="https://store-slg.cdn.vccloud.vn/LinhVuong/hot.gif">
                                    </span>
                                </div>
                            @elseif($result->is_hot == 0 && $result->is_new == 1)
                                <div class="op-top-menu" style="background-image: url('{{$result->imagemenu}}');">
                                     <span>
                                        <a href="{{$result->url_homepage}}">{{$result->name}}</a>
                                     </span>
                                    <span class="submenugig">
                                        <img src="https://store-slg.cdn.vccloud.vn/LinhVuong/new.gif">
                                    </span>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </ul>
                </li>
                <li>
                    <div id="container">
                        <div id="banner-fade" style="height: 40px; max-width: 620px; position: relative;">
                            <ul class="bjqs">
                                @foreach ($listgame as $row1 => $result)
                                    @if($result->is_hot == 1 && $result->is_new == 0)
                                        <li><a href="{{$result->url_homepage}}">
                                                <img src="https://store-slg.cdn.vccloud.vn/LinhVuong/hot.gif">{{$result->name}}
                                            </a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>