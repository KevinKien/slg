<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" href="{{ asset('/game/onepiece/zing/images/favico.ico') }}'" rel="shortcut icon"/>
    <meta name="keywords"
          content="One Piece Online, One Piece,nua hoang hai tac, slg, game hai tac, game nu hoang hai tac, vua hai tac, Vua hải tăc, hải tặc, WebGame One Piece Online hay nhất mọi thời đại , game vui, game hot, game linh vương, webgame mới">
    <title>One Piece Online</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="{{ asset('/game/onepiece/zing/js/jquery.idTabs.js') }}"></script>
    <link href="{{ asset('/game/onepiece/zing/css/showmessage.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('/game/onepiece/zing/css/mystyle.css') }}" rel="stylesheet">
    <script src="{{ asset('/game/onepiece/zing/js/showmessage.js') }}"></script>
    <script src="http://static.me.zing.vn/v3/js/zm.xcall-1.22.min.js"></script>

    <script src="{{ asset('/game/onepiece/zing/sidebar/js/jquery.cycle.js') }}"></script>
    <script src="{{ asset('/game/onepiece/zing/sidebar/js/common-ingame.js') }}"></script>
    <link href="{{ asset('/game/onepiece/zing/sidebar/css/mainsite.css') }}" rel="stylesheet">
    <link href="{{ asset('/game/onepiece/zing/sidebar/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('/game/onepiece/zing/sidebar/css/style-ingame.css') }}" rel="stylesheet">

    <script type="text/javascript">
        document.onkeydown = getKeyCode;
        var keyCode = 0;
        window.onbeforeunload = confirmExit;
        function confirmExit() {
            // F5 is 116.
            if (keyCode == 116) {
                keyCode = 0;
                // For F5
                return "Còn rất nhiều hoạt động đang đợi bạn khám phá:\nVượt ải nhận thưởng\nSăn Boss Thế Giới\nQuyết chiến bang hội\nQuyết chiến cá nhân giành ngôi vô địch";
            }
            else {
                // For close browser
                return "Còn rất nhiều hoạt động đang đợi bạn khám phá:\nVượt ải nhận thưởng\nSăn Boss Thế Giới\nQuyết chiến bang hội\nQuyết chiến cá nhân giành ngôi vô địch";
            }
        }
        function getKeyCode(e) {
            if (window.event) {
                e = window.event;
                keyCode = e.keyCode;
            }
            else {
                keyCode = e.which;
            }
        }
    </script>
</head>
<body>
<div id="sideBar" style="left: 0px;">
    <div class="side_main" id="sideBarIn">
        <div class="togger">
            <a href="#" title="Đóng" id="toggle" class="close CloseBtn">Đóng / Mở</a>

        </div>

        <div><a href="http://me.zing.vn/apps/onepieceonline" target="_blank" class="onp_logo"></a></div>
        <div class="acc_sv">
            <p>{{ $name }}</p>
            <span class="ngc"><a href="http://me.zing.vn/megift/d/code/38/124" target="_blank"></a></span>
            <select name="selectName" id="selectName" size="1">
                @foreach($servers as $server)
                    <option value="{{ $server['serverid'] }}" {{ $server['serverid'] == $recent_server_id ? 'selected="selected"' : '' }}>{{ $server['servername'] }}</option>
                @endforeach
            </select>
        </div>
        <div><a href="{{ route('onepiece-zing.payment') }}" target="_blank" class="card_bt"></a></div>
        {{--<div class="slider">--}}
            {{--<div id="banner">--}}
            {{--<a href="http://me.zing.vn/zb/dt/onepiecefpay/20812482?from=feed" target="_blank" ><img src="{{ asset('/game/onepiece/uploads/medias/2014_11_17/2_1416218175.jpg') }}'"  border="0"></a>--}}
            {{--</div>--}}
            {{--<div id="btn"></div>--}}
        {{--</div>--}}
        <div class="banner">
            <a href="http://me.zing.vn/zb/c/onepiecefpay/6844051" target="_blank" class="event_01"></a>
            <a href="http://me.zing.vn/zb/c/onepiecefpay/6844044" target="_blank" class="event_02"></a>
            <a href="http://me.zing.vn/zb/c/onepiecefpay/6844087" target="_blank" class="event_03"></a>
            <a href="http://me.zing.vn/u/onepiecefpay" target="_blank" class="event_04"></a>
        </div>
        <div><a href="http://appstore.zing.vn/?_src=as-leftgame-onepieceonline" target="_blank" class="appstore"></a>
        </div>
    </div>
</div>
<div id="iframeGame" style="left: 200px; width: 1166px;">
    <iframe frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"
            src="{{ $url }}"></iframe>
</div>

<script>

    (function (i, s, o, g, r, a, m) {
        i["GoogleAnalyticsObject"] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, "script", "//www.google-analytics.com/analytics.js", "ga");

    ga("create", "UA-55941268-1", "auto");
    ga("send", "pageview");

    $(document).ready(function () {
        $("#selectName").change(function () {
            parent.location = "http://me.zing.vn/apps/onepieceonline?_srvid=" + document.getElementById("selectName").value + '{{ Session::get('src') }}';
        });

    });

    function openFullScreen(_width, _height, _url) {
        zmXCall.getViewport(function (resp) {
            _height = resp.height - 38;
            _width = resp.width;
            zmXCall.callParent("openFullFrame", {width: _width, height: _height, url: _url});
        });
    }
    var url = window.location.href;
    openFullScreen("1310", "583", url);
</script>
@include('game.onepiece.zing.footer')
</body>
</html>
