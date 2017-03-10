<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="generator" content="Adobe GoLive" />
        <title>Linh Vương</title>
        <link rel="stylesheet" href="http://app.slg.vn/game/linhvuong_soha_server/css/styles-content.css" rel="stylesheet" type="text/css" media="all" />
        <script type="text/javascript" src="http://app.slg.vn/game/linhvuong_soha_server/js/jquery.1.4.2.min.js"></script>
        <script type="text/javascript" src="http://app.slg.vn/game/linhvuong_soha_server/js/jquery.idTabs.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <div class="container top_menu">
                <a href="#" class="logo"></a>
                <div class="user">
                    <?php if (!empty($data['usersh']->id)): ?>
                        <span style="color: #01ff70">Xin chào, <b><?php echo $data['usersh']->username; ?></b></span>
                    <?php else: ?>
                        <span class="fb-us" style="color: #01ff70">Xin chào, <b>bạn chưa đăng nhập</b></span>
                    <?php endif; ?>
                </div>
                <div class="menu">
                    <!--<span class="nav"><a href="#">Trang chủ</a>  |  <a href="#">Group</a>  |  <a href="#">Fanpage</a></span>-->
                    <span class="func">
                        <a href="http://linhvuong.sohaplay.vn/payment" target="_blank" class="card"></a>
                    </span>
                </div>
            </div>

            <div class="container server_cnt">
                <div class="content_news">
                    <div class="news_path"><a href="/">Trang chủ</a> > <a href="#">Tin tức</a></div>
                    <div class="post">
                        <div class="post_tt">
                            <?php
                            echo $data['news']->news_title;
                            ?>
                        </div>
                        <div class="post_cnt">
                            <?php
                            echo $data['news']->news_content;
                            ?>
                        </div>
                        <div class="post_other">
                            <fieldset>
                                <legend>Tin liên quan</legend>
                                <div class="news_other">
                                    <ul>
                                        <?php
                                        foreach ($data['news_list'] as $new) {
                                            //echo '<li><a href="#">[Hot] ' . $new->news_title'] . '</a></li>';
                                            echo '<li><a target="_blank" href="http://linhvuong.sohaplay.vn/newscontent?id='.$new->id.'">[Hot] ' . $new->news_title . '</a><span>' . date('d-m-Y', strtotime($new->created_at)) . '</span></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">   
                        <img src="http://app.slg.vn/game/linhvuong_soha/images/slg.png"/> 
                        <span style="display: inline-block">                                 
                            Linh Vương Truyền Kỳ do Vĩnh Xuân phát hành độc quyền tại SohaPlay .</br>
                            Email : hotro@sohagame.vn - Điện thoại: 19006639 - Hỗ trợ , báo lỗi</br>
                            Địa chỉ: Tầng 19, tòa nhà Hapulico Center Building,</br>
                            Số 1 Nguyễn Huy Tưởng, Thanh Xuân, Hà Nội                            
                        </span>
                        <img src="http://app.slg.vn/game/linhvuong_soha/images/soha.png"/>
                    </div>
        </div>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
    </body>

</html>