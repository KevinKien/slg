<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="generator" content="Adobe GoLive"/>
    <title>SLG Games</title>
    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js', true) }}"></script>
    <script src="{{ asset('plugins/tinycarousel/jquery.tinycarousel.min.js', true) }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/tinycarousel/tinycarousel.css', true) }}">
    <style type="text/css">
        body {
            font-family: "Tahoma";
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
        }

        .ft-game {
            background: #f2f2f2;
            width: 768px;
            box-shadow: 0px 0px 0px 1px #d6d6d6;
        }

        .ft-gametop {
            background: #f4f4f4;
            background: -moz-linear-gradient(top, #f4f4f4 0%, #ffffff 100%);
            background: -webkit-linear-gradient(top, #f4f4f4 0%, #ffffff 100%);
            background: linear-gradient(to bottom, #f4f4f4 0%, #ffffff 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f4f4f4', endColorstr='#ffffff', GradientType=0);
            box-shadow: inset 0px 0px 0px 1px #fff;
        }

        .ft-ph {
            display: inline-block;
            vertical-align: top;
            padding: 8px;
            width: 84px;
        }

        .ft-ph a {
            display: block;
            margin-bottom: 5px;
        }

        .ft-sld {
            display: inline-block;
        }
    </style>
</head>
<body>
@if(!empty($games))
    <div class="ft-game">
        <div class="ft-gametop">
            <div class="ft-ph">
                <a href="#">
                    <img src="{{ asset('img/slg.png', true) }}"/>
                </a>
                <div class="fb-like" data-href="https://www.facebook.com/{{ $fb }}"
                     data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
            </div>
            <div class="ft-sld" id="ftsld">
                <a class="buttons prev" href="#"></a>
                <div class="viewport">
                    <ul class="overview">
                        @foreach($games as $game)
                            <?php $images = json_decode($game['images']) ?>
                            <li><a target="_blank" href="{{ $game['url_homepage'] }}"><img src="{{ $images->iframe_slider }}"/><span>{{ $game['name'] }}</span></a></li>
                        @endforeach
                    </ul>
                </div>
                <a class="buttons next" href="#"></a>
            </div>
        </div>
        {{--<div class="ft-gamebot">--}}
            {{--<div class="ns-sld" id="nssld">--}}
                {{--<a class="buttons prev" href="#"></a>--}}
                {{--<div class="viewport">--}}
                    {{--<ul class="overview">--}}
                        {{--<li><a href="#"><img src="images/picture1.jpg"/> Tên Game - Link tin tức game--}}
                                {{--<span>- 15/01/2016</span></a></li>--}}
                        {{--<li><a href="#"><img src="images/picture2.jpg"/> Tên Game - Link tin tức game--}}
                                {{--<span>- 15/01/2016</span></a></li>--}}
                        {{--<li><a href="#"><img src="images/picture3.jpg"/> Tên Game - Link tin tức game--}}
                                {{--<span>- 15/01/2016</span></a></li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
                {{--<a class="buttons next" href="#"></a>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
    <div id="fb-root"></div>
    <script>
        $(function () {
            $('#ftsld').tinycarousel({ interval: true });
//            $('#nssld').tinycarousel({ interval: true });
        });

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
@endif
</body>
</html>