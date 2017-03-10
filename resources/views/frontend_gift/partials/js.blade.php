<script src="{!! asset('frontend_gift/js/jquery-1.9.1.min.js') !!}"></script>
<script src="{!! asset('frontend_gift/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('frontend_gift/js/vxj.js') !!}"></script>
<script src="{!! asset('frontend_gift/js/jquery.waterwheelCarousel.min.js') !!}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#carousel").waterwheelCarousel({
            separation: 350,
            animationEasing: "linear"
        });
    });
</script>
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>