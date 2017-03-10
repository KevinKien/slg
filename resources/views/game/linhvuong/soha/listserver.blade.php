<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Linh Vương</title>

        <!-- ADD BOOTSTRAP-->
        <link rel="stylesheet" href="http://app.slg.vn/tools/js/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="http://app.slg.vn/tools/js/bootstrap/css/bootstrap-theme.css">

        <script src="http://app.slg.vn/game/linhvuong_soha_server/js/jquery-1.9.1.min.js"></script>
        <script src="http://app.slg.vn/tools/js/bootstrap/js/bootstrap.js"></script>


        <link rel="stylesheet" href="http://app.slg.vn/game/linhvuong_soha_server/css/styles.css">
        <link rel="stylesheet" href="http://app.slg.vn/game/linhvuong_soha_server/css/swiper.min.css">
    </head>
    <body>
        <a href="http://sohadownloader.vcmedia.vn/games/sohaplay/SohaPlay.exe" class="rating"></a>
        <div>
            <div class="menu animated fadeIn">
                <span class="logo animated fadeInDown"></span>
                <?php if (!empty($data['usersh']->id)): ?>
                    <span style="color: #01ff70">Xin chào, <b><?php echo $data['usersh']->username; ?></b></span>
                <?php else: ?>
                    <span class="fb-us" style="color: #01ff70">Xin chào, <b>bạn chưa đăng nhập</b></span>
                <?php endif; ?>


                    <a href="http://forum.sohagame.vn/forums/3470-Huong-Dan.html" target="_blank">Thư Viện</a>
                <!--<a href="#gc" class="easy-modal-open">Giftcode</a>-->
            </div>
            <div class="menu bg"></div>
        </div>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide ts-1" data-hash="slide1">
                    <div class="container">
                        <a href="#trailer" class="trailer animated fadeInUp easy-modal-open">
                            <i class="i-play"></i>
                            <i class="i-play-cir pl-animation"></i>
                        </a>
                        <a  href="http://linhvuong.sohaplay.vn/payment" target="_blank" class="gc animated fadeInUp pay"></a>
                        <div class="event-nav">
                           <!-- <a href="#" class="sv-new animated flash">
                                <img src="http://app.slg.vn/game/linhvuong_soha_server/images/bn/sv-m-01.png"
                            </a>-->
                            <a class="animated bounce infinite" href="#" data-slide="1"><img src="http://app.slg.vn/game/linhvuong_soha_server/images/hot.png"/></a>
                        </div>
                    </div>
                    <div class="download">
                    </div>
                    <div class="social">
                        <div class="fb-like" data-href="https://www.facebook.com/sohaplay.vn/" data-layout="box_count" data-action="like" data-show-faces="true" data-share="true"></div>
                        <a href="http://forum.sohagame.vn/forums/3468-Linh-Vuong.html" target="_blank" class="fb-group">Thảo luận</a>
                    </div>
                </div>
                <div class="swiper-slide ts-2" data-hash="slide2">
                    <div class="container">
                        <div class="main-content">
                            <div class="news">
                               <!-- <a href="#" class="top-ct"></a>-->
                                <span>Tin tức - Sự kiện</span>
                                <ul>

                                    <?php
                                    foreach ($data['news'] as $new) {
                                        //echo '<li><a href="#">[Hot] ' . $new->news_title'] . '</a></li>';
                                        echo '<li><a target="_blank" href="http://linhvuong.sohaplay.vn/newscontent?id=' . $new->id . '">[Hot] ' . $new->news_title . '</a><span>' . date('d-m-Y', strtotime($new->created_at)) . '</span></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="sv-list" style="min-height: 400px;">
                                <div class="sv-side">
                                    <?php
                                    $i = count($data['listserver']) - 1;
                                    echo '<span class="tt">Máy chủ mới</span>
                                                    <a target="_blank" href="http://linhvuong.sohaplay.vn/sv?server=' . $data['listserver'][$i]['domain_server'] . '">' . $data['listserver'][$i]['servername'] . '<span style="margin-left: 45px;"></span></a>';
                                    ?>
                                </div>
                                <div class="sv-side">
                                    <span class="tt">Máy chủ đang chơi</span>
                                    <?php
                                    if (!empty($data['usersh']->id)) {
                                        foreach ($data['listserver'] as $server) {
                                            if ($server['domain_server'] == $data['server_lastest_user']) {
                                                echo '<a target="_blank" href="http://linhvuong.sohaplay.vn/sv?server=' . $server['domain_server'] . '">' . $server['servername'] . '</a>';
                                            }
                                        }
                                    }
                                    ?>

                                </div>
                                <ul class="idTabs">
                                    <li><a href="#1st" class="selected">Cụm 01-10</a></li>
                                    <li><a href="#2nd" class="">Cụm 11-20</a></li>
                                </ul>
                                <div class="tabdetail">
                                    <div id="1st" style="display: block;">
                                        <?php
                                        if (!empty($data['listserver'])) {                                           
                                            //$i = count($data['listserver']) - 1;                                                                                      
                                            //echo '<a target="_blank" href="http://linhvuong.sohaplay.vn/sv?server=' . $data['listserver'][$i]['domain_server'] . '">' . $data['listserver'][$i]['servername'] . '</a>';
                                           //----------load ra số lượng server thiết lập---------
                                            //$server = $data['listserver'];
//                                            foreach ($data['listserver'] as $server) {                                                
                                               // if (!empty($data['usersh']->id)) {
//                                                    echo '<a target="_blank" href="http:linhvuong.sohaplay.vn/sv?server=' . $server['domain_server'] . '">' . $server['servername'] . '</a>';
//                                                } else {
//                                                    echo '<a data-toggle="modal" data-target="#myModal" target="_blank" href="http:linhvuong.sohaplay.vn/sv?server=' . $server['domain_server'] . '">' . $server['servername'] . '</a>';
//                                                }
//                                            }
                                            //--------load toàn bộ server--------
                                            foreach ($data['listserver'] as $server) {                                                
                                                if ($server['is_new'] == "1") {
                                                    echo '<a target="_blank" href="http://linhvuong.sohaplay.vn/sv?server=' . $server['domain_server'] . '">' . $server['servername'] . '</a>';
                                                }
                                            }
                                        }
                                        ?>
                                    </div><!--
                                    <div id="2nd" style="display: none;">
                                        
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <img src="http://app.slg.vn/game/linhvuong_soha/images/slg.png"/>
                        <span style="display: inline-block;">                                      
                            Linh Vương Truyền Kỳ phát hành độc quyền tại SohaPlay .</br>
                            Email : hotro@sohagame.vn - Điện thoại: 19006639 - Hỗ trợ , báo lỗi  </br>
                            Địa chỉ: Tầng 19, tòa nhà Hapulico Center Building,</br>
                            Số 1 Nguyễn Huy Tưởng, Thanh Xuân, Hà Nội
                        </span>
                        <img src="http://app.slg.vn/game/linhvuong_soha/images/soha.png"/>
                    </div>
                </div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <!-- Modal -->
        <div class="easy-modal" id="trailer">
            <iframe width="800" height="480" src="https://www.youtube.com/embed/mXo-XDQWjw0" frameborder="0"></iframe>
        </div>
        <div class="easy-modal" id="gc">
            <div class="gc-in">
                <span>Giftcode Thần Tiên Đạo</span>
                Giftcode sẽ được phát vào
                <b>
                    <div id="clockdiv">
                        <div>
                            <span class="days"></span>
                            <div class="smalltext">Ngày</div>
                        </div>
                        <div>
                            <span class="hours"></span>
                            <div class="smalltext">Giờ</div>
                        </div>
                        <div>
                            <span class="minutes"></span>
                            <div class="smalltext">Phút</div>
                        </div>
                        <div>
                            <span class="seconds"></span>
                            <div class="smalltext">Giây</div>
                        </div>
                    </div>
                </b>
                , mời bạn quay lại sau
            </div>
        </div>
        <!-- Modal -->

        <script src="http://app.slg.vn/game/linhvuong_soha_server/js/swiper.min.js"></script>
        <script src="http://app.slg.vn/game/linhvuong_soha_server/js/jquery.easyModal.js"></script>
        <script type="text/javascript" src="http://app.slg.vn/game/linhvuong_soha_server/js/jquery.idTabs.js"></script>
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
        <script>
            var myswiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                direction: 'vertical',
                slidesPerView: 1,
                paginationClickable: true,
                mousewheelControl: true,
                hashNav: true
            });
            $('a[data-slide]').click(function (e) {
                e.preventDefault();
                myswiper.slideTo($(this).data('slide'));
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
        <script type="text/javascript">
            $(function () {
                $('.easy-modal').easyModal({
                    top: 200,
                    overlay: 0.2
                });

                $('.easy-modal-open').click(function (e) {
                    var target = $(this).attr('href');
                    $(target).trigger('openModal');
                    e.preventDefault();
                });

                $('.easy-modal-close').click(function (e) {
                    $('.easy-modal').trigger('closeModal');
                });
            });
        </script>
        <script>
            function getTimeRemaining(endtime) {
                var t = Date.parse(endtime) - Date.parse(new Date());
                var seconds = Math.floor((t / 1000) % 60);
                var minutes = Math.floor((t / 1000 / 60) % 60);
                var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
                var days = Math.floor(t / (1000 * 60 * 60 * 24));
                return {
                    'total': t,
                    'days': days,
                    'hours': hours,
                    'minutes': minutes,
                    'seconds': seconds
                };
            }

            function initializeClock(id, endtime) {
                var clock = document.getElementById(id);
                var daysSpan = clock.querySelector('.days');
                var hoursSpan = clock.querySelector('.hours');
                var minutesSpan = clock.querySelector('.minutes');
                var secondsSpan = clock.querySelector('.seconds');

                function updateClock() {
                    var t = getTimeRemaining(endtime);

                    daysSpan.innerHTML = t.days;
                    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                    if (t.total <= 0) {
                        clearInterval(timeinterval);
                    }
                }

                updateClock();
                var timeinterval = setInterval(updateClock, 1000);
            }

            var deadline = 'November 30 2015 16:00:00 GMT+07:00';
            initializeClock('clockdiv', deadline);

        </script>
        <!-- Button trigger modal -->
        <!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
            Launch demo modal
        </button>-->

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Linh Vương Soha</h4>
                    </div>
                    <div class="modal-body">
                        Bạn hãy đăng nhập vào hệ thống.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>