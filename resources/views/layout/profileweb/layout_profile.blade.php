<!DOCTYPE html>
@include('layout.profileweb.htmlheader')
<body>
    @include('layout.profileweb.mainheader')
    <section id="ContWrap">
        @include('layout.profileweb.breadcrumbs')
        @yield('content')
    </section>
    <article class="ContentsBannerWrap">
        <div id="div-gpt-ad-1358234005280-3" style="width:728px; height:90px;margin:0 auto;">

        </div>
    </article>
    @include('layout.profileweb.footer')
    @yield('js-current')
</body>
</html>