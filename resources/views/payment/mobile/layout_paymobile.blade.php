<!DOCTYPE html>
@include('payment.mobile.htmlheader')
<body class="cbp-spmenu-push">
    @include('payment.mobile.mainheader')
    <div class="container-fluid">
        @yield('content')
    </div>
    @yield('js-current')
</body>
</html>