<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="generator" content="Adobe GoLive" />
        <title>@yield('title', 'SLG') | SLG</title>
        <link href="https://app.slg.vn/fbapp/thienlongtruyenky/css/styles.css" rel="stylesheet" type="text/css" media="all" />
        <link href="https://app.slg.vn/fbapp/thienlongtruyenky/css/swiper.min.css" rel="stylesheet" type="text/css" media="all" />
        <link href="https://app.slg.vn/fbapp/thienlongtruyenky/css/animate.css" rel="stylesheet" type="text/css" media="all" />

        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/jquery.1.4.2.min.js"></script>
        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/swiper.min.js"></script>
        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/jquery.easyModal.js"></script>
        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/jquery.idTabs.js"></script>
        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/jquery.cycle.js"></script>
        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/jquery.tinycarousel.min.js"></script>
        <script type="text/javascript" src="https://app.slg.vn/fbapp/thienlongtruyenky/js/invite.js"></script>

    </head>

    <!--@extends('fbapp.thienlongtruyenky.layout_serverlist')

    @section('content')-->
    <body>                
        <div>
        <div class="menu animated fadeIn">
            <span class="logo animated fadeInDown"></span>
            <span class="fb-us">
                @if(is_object(Auth::user()) &&  Auth::user()->name )
                <span>Xin chào, <b>{{ !empty(Auth::user()->fullname) ? Auth::user()->fullname : Auth::user()->name }}</b></span>
                @else
                <span>Xin chào, <b>Guest</b></span>
                @endif
            </span>
            <span class="func">
                <a href="#" class="card"></a>
                <a href="#" onclick="invite();" class="invite">Invite</a>            
                <div class="fb-like" data-href="https://www.facebook.com/thienlongtruyenky.SLG" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
            </span>
        </div>
        <div class="menu bg"></div>
    </div>

    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide ts-1" data-hash="slide1">
                <div class="container">
                    <a href="http://cdn.slg.vn/thienlong/20150825_TLTK_OB.rar" target="_blank" class="gc animated pulse infinite pay "></a>
                    <div class="gc">
                        <a href="http://cdn.slg.vn/thienlong/20150825_TLTK_OB.apk" target="_blank" class="android"></a>
                        <a href="javascript:void(0);" onclick="window.location='itms-services://?action=download-manifest&url=https://slg6.cdn.vccloud.vn/thienlong/sdk/ios/thienlong.plist'" class="apple"></a>
                        <a href="http://cdn.slg.vn/thienlong/20150825_TLTK_OB.rar" target="_blank" class="pcomp"></a>

                    </div>


                    <div class="event-nav">
                        <a href="#" data-slide="1"><img class="animated infinite" src="https://app.slg.vn/fbapp/thienlongtruyenky/images/hot.png"/></a>
                    </div>
                </div>
                <div class="download">
                </div>
    <!--            <div class="social">
                    <a href="#" class="fb-group">Invite</a>
                    <a href="#" class="fb-group">Like</a>
                    <a href="#" class="fb-group">Share</a>

                </div>-->
            </div>
            <div class="swiper-slide ts-2" data-hash="slide2">
                <div class="container">
                    <div class="main-content">
                       <div class="news">
                        <!--<a href="#" class="top-ct"></a>-->
                        <span>Tin tức - Sự kiện</span>
                         <?php                          
                            if(isset($news)){                            
                                $html="";
                                                   
                                    for($i=0; $i < 6; $i++)
                                    {                           	                                                                                             		
                                    $html.='
                                        <ul>
                                        <li>                                        
                                        <a target="_blank" href="http://tl.slg.vn/home/content/'.$news->data[$i]->news_id.'/'.$news->data[$i]->news_type.'">'.$news->data[$i]->news_title.'</a>
                                        </li></ul>';                                                                                      
                                    }
                            echo $html;
                            }else
                            print_r("data null");
                        ?>	
                        </div>
                        <div class="news2">
                            <!--Slide-->
                            <div id="slide">
                                <div id="banner">
                                    <a href="#"><img src="https://app.slg.vn/fbapp/thienlongtruyenky/images/background.jpg" border="0"></a>
                                    <a href="#"><img src="https://app.slg.vn/fbapp/thienlongtruyenky/images/background.jpg" border="0"></a>
                                    <a href="#"><img src="https://app.slg.vn/fbapp/thienlongtruyenky/images/background.jpg" border="0"></a>
                                    <a href="#"><img src="https://app.slg.vn/fbapp/thienlongtruyenky/images/background.jpg" border="0"></a>
                                    <a href="#"><img src="https://app.slg.vn/fbapp/thienlongtruyenky/images/background.jpg" border="0"></a>
                                </div>
                                <div id="btn"></div>
                            </div>
                            <!--Slide-->
                        </div>
                    </div>
                </div>



                <div class="footer">
                    <img src="https://app.slg.vn/fbapp/thienlongtruyenky/images/slg.png"/> <span>Bản quyền thuộc về Boat6 - Cty CP DV trực tuyến Vĩnh Xuân độc quyền phát hành tại Việt Nam<br>
                    Hotline: 043.5.380.202 - Email: hotro@slg.vn</span>
                </div>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <!-- Modal -->
    <!-- Modal -->
    <div id="fb-root"></div>

    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <script>
        var myswiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            direction: 'vertical',
            slidesPerView: 1,
            paginationClickable: true,
            mousewheelControl: true,
            hashNav:true
        });
        $('a[data-slide]').click(function(e){
            e.preventDefault();
            myswiper.slideTo( $(this).data('slide') );
        })
    </script>
    <script>
        var swiper = new Swiper('.swiper-char', {
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            parallax: true,
            speed: 600,
        });
    </script>
<!--    <script type="text/javascript">
        $(function () {
            $('#banner').cycle({
                fx: 'fade',
                pager: '#btn'
            });
        })
    </script>-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#slider1').tinycarousel();
        });
    </script>    
    </body>

    
    
</html>