<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#e59403">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title')</title>
    <!--css-->
    @include('frontend_gift.partials.css')
    <!--css custom-->
    @yield("css_custum")
</head>
<body>
<!--Nav-->
    @include('frontend_gift.pages.header-menu')
<!--Main-->
    @yield('content')
<!--Footer-->
    @include('frontend_gift.pages.footer')
<!--js-->
    @include('frontend_gift.partials.js')
<!--css custom-->
    @yield("js_custum")
</body>
</html>