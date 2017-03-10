<!DOCTYPE html>
@include('payment.web.htmlheader')
<body>
    @include('payment.web.mainheader')
    <section id="ContWrap">
        @include('payment.web.breadcrumbs')
        @include('payment.web.colleft')
        @yield('content')
    </section>
    <article class="ContentsBannerWrap">
        <div id="div-gpt-ad-1358234005280-3" style="width:728px; height:90px;margin:0 auto;">

        </div>
    </article>
    @include('payment.web.footer')
    @yield('js-current')
</body>
</html>