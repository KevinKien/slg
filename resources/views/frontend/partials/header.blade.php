<!-- navigation bar-->
<header class="container-fluid">
    <nav class="navbar navbar-default mynav navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img class="img-responsive"
                                                      src="{{ secure_asset('/frontend/images/02-homepage_03.png') }}"></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="//slg.vn">Trang chủ</a></li>
                    <li><a href="#games">Games</a></li>
                    <li{{ (contains(Route::getCurrentRoute()->getAction()['domain'], 'pay.')) ? ' class=active' : '' }}>{!! HTML::link(route('topupcash'), 'Nạp tiền') !!}</li>
                    <li><a href="#lienhe">Liên hệ</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::check())
                        <li><a href="{{ str_replace('https:', 'http:', route('profile')) }}">Xin chào, {{ !empty(Auth::user()->fullname) ? Auth::user()->fullname : Auth::user()->name }}</a></li>
                        <li><a href="{{ route('logout') }}">Thoát</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>