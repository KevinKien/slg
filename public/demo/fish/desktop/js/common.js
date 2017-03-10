$(document).ready(function($){
    jQuery('#slider').taSlider({
        snapToChildren: true,
        desktopClickDrag: false,
        infiniteSlider: true,
        snapSlideCenter: true,
        autoSlide: true,
        autoSlideTimer: 2000,
        navSlideSelector: jQuery('.control li a'),
        navPrevSelector: jQuery('.btn-prev'),
        navNextSelector: jQuery('.btn-next'),
        onSlideChange: slideContentChange,
        onSliderLoaded: slideContentLoaded
    });
    if($("#device a").hasClass("active")){
        var id = $("#device a").data("id");
        $("#"+id).stop().fadeIn();
    }
    $("#device a").on("click",function(){
        $("#device a").removeClass("active");
        $(this).addClass("active");
        var id = $(this).data("id");
        $(".box-device .tab-content").css({display:"none"});
        $("#"+id).stop().fadeIn();
    });
    $(".ul-tab li a").click(function(){
        $(".ul-tab li a").removeClass("active");
        var id = $(this).data("id");
        $(".box-aside .item").removeClass("active");
        $("#"+id).addClass("active");
        $(this).addClass("active");
    });
    if($("#ul-media li a").hasClass("active")){
        var index = $("#ul-media li a").parent("li").index();
        $(".tab-media").eq(index).css({display:"block"});
        $("#media-"+index+"").shgCarousel({
            visible: 3,
            scroll: 3,
            circular: true,
            btnNext: "#btn-next-"+index+"",
            btnPrev: "#btn-prev-"+index+""
        });
    }
    $("#ul-media li a").click(function(){
        $("#ul-media li a").removeClass("active");
        $(".tab-media").css({display:"none"});
        var index =  $(this).parent("li").index();
        $(this).addClass("active");
        $(".tab-media").eq(index).css({display:"block"});
        $("#media-"+index+"").shgCarousel({
            visible: 3,
            scroll: 3,
            circular: true,
            btnNext: "#btn-next-"+index+"",
            btnPrev: "#btn-prev-"+index+""
        });
    });
});
function slideContentChange(args) {
    jQuery(".taSlider .slider .item").removeClass("active");
    jQuery(args.currentSlideObject).addClass("active");
    jQuery(args.sliderObject).parent().find('.control li a').removeClass('cur');
    jQuery(args.sliderObject).parent().find('.control li:eq(' + (args.currentSlideNumber - 1) + ') a').addClass('cur');

}
function slideContentLoaded(args) {
    jQuery(".taSlider .slider .item").removeClass("active");
    jQuery(args.currentSlideObject).addClass("active");
    jQuery(args.sliderObject).parent().find('.control li a').removeClass('cur');
    jQuery(args.sliderObject).parent().find('.control li:eq(' + (args.currentSlideNumber - 1) + ') a').addClass('cur');

}
function facebook() {
    u = location.href;
    t = document.title;
    window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');
    return false;
}
function linkhay() {
    u = location.href;
    t = document.title;
    window.open('http://linkhay.com/submit?link_url=' + encodeURIComponent(u) + '&link_title=' + t, 'sharer', 'toolbar=0,status=0,width=626,height=436');
}
function zing() {
    u = location.href;
    t = document.title;
    window.open('http://link.apps.zing.vn/share?u=' + encodeURIComponent(u) + '&t=' + t, 'sharer', 'toolbar=0,status=0,width=626,height=436');
}
function taigame () {
    window.location.href = "http://caycanhbonsai.vn";
}