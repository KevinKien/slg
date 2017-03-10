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

    <link href="{{ asset('/game/onepiece/zing/css/style.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <link href="http://css.me.zdn.vn/zmjs/zm.ui-1.46.css" media="screen" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="http://static.me.zing.vn/v3/sdk-js/zmCore-1.54.min.js"></script>
    <script type="text/javascript" src="http://static.me.zing.vn/v3/js/zm.xcall-1.22.min.js"></script>
    <script type="text/javascript" src="http://static.me.zing.vn/v3/sdk-js/zm.ui-1.75.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="http://static.me.zing.vn/v3/sdk-js/sdkjs-1.00.min.js" charset="utf-8"></script>
    <link href="{{ asset('/game/onepiece/zing/css/showmessage.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <script src="{{ asset('/game/onepiece/zing/js/showmessage.js') }}"></script>
    <script type="text/javascript">
        function openBoxy(title, url, width, height) {
            zmjsSDKInCanvas.openBoxy(title, url, width, height);
        }

        zmXCall.register('callbackDeposit', function (data) {

            zmXCall.call('closeBoxy');
            window.location.reload();
        });

        zmjsSDKInCanvas.callbackPayment = function (data) {
            window.location.reload();
        };

        $(document).ready(function () {
            if ($.browser.msie && $.browser.version <= 6) {
                $('#browseWarning').css({'color': '#ff0000'}).html('Bạn nên sử dụng trình duyệt IE7 trở lên');
            }

            $(".goldinput").keyup(function () {
                $("#goldvalue").text($(".goldinput").val());
                $("input[type='radio']").attr("checked", false);
            });
        });
    </script>
<body>
<div class="loadbox">
    <div class="headline"><a onclick="openBoxy('Ví Zing Me','http://credits-me.zing.vn/v2/deposit?_t=2', 910, 730);"
                             href="#" class="loadzgold"></a> <span class="headtitle"></span></div>
    <div class="info">

        <form id="myForm" name="frontForm" action="{{ route('onepiece-zing.billing') }}" method="post">

            <p>Tên tài khoản: <strong>{{ $username }}</strong></p>
            <p><span class="zinggold"></span> <span class="mygold">{{ $balance }}</span></p>
            <p>Chọn máy chủ đê nạp vàng:
                <select name="server" id="server" style="width:200px;" class="server">
                    <option value="">Chọn máy chủ</option>
                    @foreach($servers as $server)
                        <option value="{{ $server['serverid'] }}">{{ $server['servername'] }}</option>
                    @endforeach
                </select>
            </p>
            <div class="ratecom">
                <p>
                    <input name="coins" id="coins1" type="radio" class="select" value="200"/>
                    <strong>200</strong> <span class="zinggold"></span><span class="symbol">=</span>
                    <strong>200</strong><span class="goldicn"></span></p>
                <p>
                    <input name="coins" id="coins2" type="radio" class="select" value="500"/>
                    <strong>500</strong> <span class="zinggold"></span><span class="symbol">=</span>
                    <strong>500</strong><span class="goldicn"></span></p>
                <p>
                    <input name="coins" id="coins2" type="radio" class="select" value="1200"/>
                    <strong>1200</strong> <span class="zinggold"></span><span class="symbol">=</span>
                    <strong>1200</strong><span class="goldicn"></span></p>
                <p>
                    <input name="coins" id="coins3" type="radio" class="select" value="2400"/>
                    <strong>2400</strong> <span class="zinggold"></span><span class="symbol">=</span>
                    <strong>2400</strong><span class="goldicn"></span></p>
                <p>
                    <input name="coins" id="coins4" type="radio" class="select" value="4800"/>
                    <strong>4800</strong> <span class="zinggold"></span><span class="symbol">=</span>
                    <strong>4800</strong><span class="goldicn"></span></p>
                <p>
                    <input name="coins" id="coins5" type="radio" class="select" value="7200"/>
                    <strong>7200</strong> <span class="zinggold"></span><span class="symbol">=</span>
                    <strong>7200</strong><span class="goldicn"></span></p>
            </div>
            <p>Nhập số Zing xu muốn nạp:
                <input type="text" class="goldinput" id="goldinput" name="goldinput"/>
                <span style="height:8px; width:8px;">=</span>
                <strong id="goldvalue"></strong><span class="goldicn"></span></p>
            <div class="footcom"><a href="#" id="submit_pay" name="submit_pay" class="loadbtn"></a>
                <a href="http://me.zing.vn/zb/dt/onepiecefpay/20820241" target="_blank" class="helpbtn"></a>
                <div class="clr"></div>
            </div>

            <input type="hidden" name="g" value="16"/>
            <input type="hidden" name="token" value="b9b86c94885d29c57a777ee039fbe994"/>

        </form>

        <script>
            $(document).ready(function () {

                $("#goldinput").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // Allow: Ctrl+A
                            (e.keyCode == 65 && e.ctrlKey === true) ||
                            // Allow: home, end, left, right
                            (e.keyCode >= 35 && e.keyCode <= 39)) {
                        // let it happen, don't do anything
                        return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });
                $('input[type=radio]').live('change', function () {

                    var goldinput = document.getElementById('goldinput');
                    goldinput.value = "";
                    $("#goldvalue").text("");

                });
                $("#submit_pay").click(function () { // triggred click

                    var goldinput = document.getElementById('goldinput');

                    var cboServer = document.getElementById('server');
                    if (cboServer.value == "") {
                        alert("Bạn hãy chọn server cần nạp.");
                    }
                    var rates = document.getElementsByName('coins');
                    var rate_value;
                    for (var i = 0; i < rates.length; i++) {
                        if (rates[i].checked) {
                            rate_value = rates[i].value;
                        }
                    }
                    if (rate_value == null && goldinput.value == "") {
                        alert("Bạn hãy chọn số tiền cần nạp");
                        return false;
                    }

                    if (goldinput.value != "") {
                        if (goldinput.value % 100 != 0) {
                            alert("Số tiền cần nạp phải là bội của 100");
                            goldinput.focus();
                            return false;
                        }
                        else {
                            rate_value = goldinput.value;
                        }
                    }

                    $.post('{{ route('onepiece-zing.billing') }}', {server_id: cboServer.value, amount: rate_value}
                            , function (value) {
                                if (value == '-1' || value == -1) {
                                    alert("Xin vui lòng đăng nhập");
                                }
                                else if (value == '-2' || value == -2) {
                                    alert("Có lỗi xảy ra trong quá trình giao dịch, bạn vui lòng kiểm tra lại thông tin");
                                }
                                else
                                    window.location = value;
                            });
                });

            });
        </script>
    </div>
</div>
@include('game.onepiece.zing.footer')
</body>
</html>