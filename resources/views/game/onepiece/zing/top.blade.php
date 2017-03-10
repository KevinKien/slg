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
    <link href="{{ asset('/game/onepiece/zing/css/styles_top.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <a a href="#" onclick="window.top.location.href = '{{ $zing_url }}'" style="white-space: normal;" class="back">← Quay lại</a>
    <div class="top">
        <span class="ico">TOP 20 Cao thủ</span>
        <div>
            <table border="0" cellspacing="1" cellpadding="0">
                <tr class="head">
                    <td class="tt">Hạng</td>
                    <td>Tên nhân vật</td>
                    <td>{{ $type == 2 ? 'Lực chiến' : 'Level' }}</td>
                </tr>
                @if(is_array($tops))
                    @foreach($tops as $i => $top)
                        <?php
                        ++$i;

                        if ($i == 1) {
                            $class = 'one';
                        } elseif ($i == 2) {
                            $class = 'two';

                        } elseif ($i == 3) {
                            $class = 'three';

                        } else {
                            $class = 'stripe';

                        }
                        ?>
                        <tr class="{{ $class }}">
                            <td class="tt">{{ $top['rank'] }}</td>
                            <td>{{ $top['name'] }}</td>
                            <td>{{ $type == 2 ? $top['power'] : $top['level'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
    <div class="option">
        <form>
            <label>Chọn máy chủ</label>
            <select name="selectName" id="selectName" size="1">
                @foreach($servers as $server)
                    <option value="{{ $server['serverid'] }}" {{ $server['serverid'] == $server_id ? 'selected="selected"' : '' }}>{{ $server['servername'] }}</option>
                @endforeach
            </select>
            <label>TOP theo Level/Lực chiến</label>
            <select name="selectType" id="selectType" size="1">
                <option {{ $type == 2 ? 'selected="selected"' : '' }} value="2">Lực chiến</option>
                <option {{ $type == 1 ? 'selected="selected"' : '' }} value="1">Level</option>
            </select>
        </form>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {

        $("#selectName").change(function () {
            window.location.href = '{{ route('onepiece-zing.top') }}/' + document.getElementById("selectName").value + "/" + document.getElementById("selectType").value;
        });
        $("#selectType").change(function () {
            window.location.href = '{{ route('onepiece-zing.top') }}/' + document.getElementById("selectName").value + "/" + document.getElementById("selectType").value;
        });
    });
</script>

<!-- Google Code dành cho Th? ti?p th? l?i -->
<!--------------------------------------------------
Không th? liên k?t th? ti?p th? l?i v?i thông tin nh?n d?ng cá nhân hay d?t th? ti?p th? l?i trên các trang có liên quan d?n danh m?c nh?y c?m. Xem thêm thông tin và hu?ng d?n v? cách thi?t l?p th? trên: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 966084680;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $.getScript('http://www.googleadservices.com/pagead/conversion.js');
    });
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt=""
             src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/966084680/?value=0&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>

<div style='width:1px;height:1px;display:none'><img src='http://static.novanet.vn/adverclient.js?code=1220'/></div>


<script type="text/javascript" class="microad_blade_track">
    <!--
    var microad_blade_gl = microad_blade_gl || {'params': new Array(), 'complete_map': new Object()};
    (function () {
        var param = {'co_account_id': '9920', 'group_id': '', 'country_id': '5', 'ver': '2.1.0'};
        microad_blade_gl.params.push(param);

        var src = (location.protocol == 'https:')
                ? 'https://d-cache.microadinc.com/js/blade_track_gl.js' : 'http://d-cache.microadinc.com/js/blade_track_gl.js';

        var bs = document.createElement('script');
        bs.type = 'text/javascript';
        bs.async = true;
        bs.charset = 'utf-8';
        bs.src = src;

        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(bs, s);
    })();
    -->
</script>
<div style='width:1px;height:1px;display:none'><img src='http://static.novanet.vn/adverclient.js?code=1229'/></div>
@include('game.onepiece.zing.footer')
</html>