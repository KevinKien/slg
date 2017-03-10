var countItem = null;
var widthSlider = null;
var widthDevice = window.innerWidth;
jQuery(document).ready(function($) {
    countItem = jQuery(".slider .item").length;
    widthSlider = parseInt(countItem) * 320;
    checkSlider();
    jQuery('.taSlider').taSlider({
        snapToChildren: true,
        desktopClickDrag: true,
        infiniteSlider: true,
        snapSlideCenter: true,
        autoSlide: true,
        autoSlideTimer: 2000000,
        navSlideSelector: jQuery('.control li a'),
        navPrevSelector: jQuery('.btn-prev'),
        navNextSelector: jQuery('.btn-next'),
        onSlideChange: slideContentChange,
        onSliderLoaded: slideContentLoaded
    });
    $(".ul-tab li a").click(function(){
        $(".ul-tab li a").removeClass("active");
        var id = $(this).data("id");
        $(".baside .item").removeClass("active");
        $("#"+id).addClass("active");
        $(this).addClass("active");
    });
});
function checkSlider(){
    if(widthSlider < widthDevice){
        jQuery(".bslide").css({width:"320px",margin:"0 auto"});
    }else{
        if(widthSlider/widthDevice<1.5){
            jQuery(".bslide").css({width:"320px",margin:"0 auto"});
        }
    }
}
window.addEventListener("orientationchange", function() {
    checkSlider();
    jQuery('.taSlider').taSlider({
        snapToChildren: true,
        desktopClickDrag: true,
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
}, false);

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