<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="" />
    <meta name="author" content="roussounelos nikos" />

    <link rel="stylesheet" href="{{ asset('errors/css/main.css') }}" type="text/css" media="screen, projection" /> <!-- main stylesheet -->
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('errors/css/tipsy.css') }}" /> <!-- Tipsy implementation -->

    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="{{ asset('errors/css/ie7.css') }}" />
    <![endif]-->

    <script type="text/javascript" src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script> <!-- jQuery implementation -->
    <script type="text/javascript" src="{{ asset('errors/scripts/custom-scripts.js') }}"></script><!-- All of my custom scripts -->
    <script type="text/javascript" src="{{ asset('errors/scripts/jquery.tipsy.js') }}"></script> <!-- Tipsy -->

    <script type="text/javascript">

        $(document).ready(function(){

            universalPreloader();

        });

        $(window).load(function(){

            //remove Universal Preloader
            universalPreloaderRemove();

            rotate();
            dogRun();
            dogTalk();

            //Tipsy implementation
            $('.with-tooltip').tipsy({gravity: $.fn.tipsy.autoNS});

        });

    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Không tìm thấy trang - 404</title>
</head>

<body>

<!-- Universal preloader -->
<div id="universal-preloader">
    <div class="preloader">
        <img src="{{ asset('errors/images/universal-preloader.gif') }}" alt="universal-preloader" class="universal-preloader-preloader"/>
    </div>
</div>
<!-- Universal preloader -->

<div id="wrapper">
    <!-- 404 graphic -->
    <div class="graphic">
        <img src="{{ asset('errors/images/404.png') }}" alt="404" />
    </div>
    <!-- 404 graphic -->

    <!-- Text, search form and menu -->
    <div class="top-left">
        <!-- Not found text -->
        <div class="not-found-text">
            <h1 class="not-found-text">Xin lỗi, chúng tôi không tìm thấy trang bạn yêu cầu.</h1>
        </div>
        <!-- Not found text -->

        <!-- search form -->
        {{--<div class="search">--}}
            {{--<form name="search" method="get" action="#">--}}
                {{--<input type="text" name="search" value="Search ..." />--}}
                {{--<input class="with-tooltip" title="Search!" type="submit" name="submit" value="" />--}}
            {{--</form>--}}
        {{--</div>--}}
        <!-- search form -->

        <!-- top menu -->
        <div class="top-menu">
            <a href="{{ route('topupcash') }}" class="with-tooltip" title="Về trang chủ">Trang chủ</a> | <a href="{{ route('topupcash.index') }}#lienhe" class="with-tooltip" title="Liên hệ với chúng tôi!">Liên hệ</a>
        </div>
        <!-- top menu -->
    </div>
    <!-- Text, search form and menu -->

    <!-- planet at the bottom -->
    <div class="planet">
        <div class="dog-wrapper">
            <!-- dog running -->
            <div class="dog"></div>
            <!-- dog running -->

            <!-- dog bubble talking -->
            <div class="dog-bubble">

            </div>

            <!-- The dog bubble rotates these -->
            <div class="bubble-options">
                <p class="dog-bubble">
                    Are you lost, bud? No worries, I'm an excellent guide!
                </p>
                <p class="dog-bubble">
                    <br />
                    Arf! Arf!
                </p>
                <p class="dog-bubble">
                    <br />
                    Don't worry! I'm on it!
                </p>
                <p class="dog-bubble">
                    I wish I had a cookie<br /><img style="margin-top:8px" src="{{ asset('errors/images/cookie.png') }}" alt="cookie" />
                </p>
                <p class="dog-bubble">
                    <br />
                    Geez! This is pretty tiresome!
                </p>
                <p class="dog-bubble">
                    <br />
                    Am I getting close?
                </p>
                <p class="dog-bubble">
                    Or am I just going in circles? Nah...
                </p>
                <p class="dog-bubble">
                    <br />
                    OK, I'm officially lost now...
                </p>
                <p class="dog-bubble">
                    I think I saw a <br /><img style="margin-top:8px" src="{{ asset('errors/images/cat.png') }}" alt="cat" />
                </p>
                <p class="dog-bubble">
                    What are we supposed to be looking for, anyway? @_@
                </p>
            </div>
            <!-- The dog bubble rotates these -->
            <!-- dog bubble talking -->
        </div>

        <!-- planet image -->
        <img src="{{ asset('errors/images/planet.png') }}" alt="planet" />
        <!-- planet image -->
    </div>
    <!-- planet at the bottom -->
</div>

</body>
</html>
