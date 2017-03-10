<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>Side</title>
        <link href="http://app.slg.vn/game/onepiece/styles.css" rel="stylesheet" type="text/css" media="all" />
         jQuery library (served from Google) 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
         bxSlider Javascript file 
        <script src="http://app.slg.vn/game/onepiece/jquery.cycle.js"></script>
        <script src="http://app.slg.vn/game/onepiece/common-ingame.js"></script>
    </head>

    <body>
        <div class="side_main" id="static_reg" style="left: 0px;">
            <script type="text/javascript">
                    
                function toggle_visibility(id) {
                        var e = document.getElementById(id);
                        if (e.style.left == '0px')
                            e.style.left = '-180px';
                        else
                            e.style.left = '0px';
                    }
                    //
            </script>
            <div class="togger" >
                <a id="toggle" class="close CloseBtn" title="Đóng" class="close">
                    <img src="images/slide_out.png">
                </a>
            </div>

            <div><a href="#" class="slg_logo"></a></div>
            <div><a href="#" class="onp_logo"></a></div>
            <div class="acc_sv">
                <p>UsernameUser</p>
                <a href="#" class="acc_quit">THOÁT</a>
                <span class="ngc"><a href="#openModal"></a></span>
                <select name="selectName" size="1">
                    <option value="value">Máy chủ đang chơi</option>
                    <option value="value">S1 - Luffy</option>
                    <option value="value">S1 - Luffy</option>
                </select>
            </div>
            <div><a href="#" class="card_bt"></a></div>
            <div class="slider">
                <div id="banner">
                    <a href="#"><img src="images/01.jpg"  border="0"></a>
                    <a href="#"><img src="images/02.jpg" border="0"></a>
                    <a href="#"><img src="images/03.jpg" border="0"></a>
                    <a href="#"><img src="images/04.jpg" border="0"></a>
                </div>
                <div id="btn"></div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#banner').cycle({
                        fx: 'fade',
                        pager: '#btn'
                    });
                })
            </script>
            <div class="banner">
                <a href="#" class="event_02"></a>
                <a href="#" class="event_03"></a>
                <a href="#" class="event_05"></a>
            </div>
        </div>

        <div id="openModal" class="modalDialog">
            <div>
                <a href="#close" title="Close" class="close">X</a>
                <h2>Nhận Giftcode</h2>
                <form>
                    <table border="0" cellspacing="1" cellpadding="0">
                        <tr>
                            <td class="head">Loại Giftcode</td>
                            <td class="head">Tình trạng</td>
                        </tr>
                        <tr>
                            <td>Giftcode Tân thủ</td>
                            <td><b>9846131564987321354</b></td>
                        </tr>
                        <tr>
                            <td>Giftcode Loằng ngoằng</td>
                            <td><b>9846131564987321354</b></td>
                        </tr>
                        <tr>
                            <td>Giftcode Fanpage</td>
                            <td><a href="#register" class="reg">Nhận</a></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        -->
        <!--<div id="iframeGame" style="left: 200px; width: 1166px;">-->
            <iframe style="overflow:hidden;height:100%;width:100%" src="{{$url}}" frameborder="0" height="100%" width="100%"></iframe>
        <!--</div>-->
<!--    </body>

</html>-->