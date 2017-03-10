jQuery(document).ready(function(){
	
	var isClose = false;
	if(jQuery("#toggle").length > 0){
		$('#iframeGame').animate({
			'left': 200,
			'width': $(window).width() - 200
		});
		console.log($(window).width() - 200);
		jQuery("#toggle").click(function(){
			if(!isClose){
				jQuery(this).removeClass("CloseBtn");
				jQuery(this).addClass("OpenBtn");
				jQuery("#sideBar").animate({
					left: '-180'
				});
				
				jQuery("#iframeGame").animate({
					left: '20',
					width: $(window).width() - 20
				});
				console.log($(window).width() - 20);
				jQuery(this).attr({title : 'Mở'});
				isClose = true;
				
			}else{
				jQuery(this).removeClass("OpenBtn");
				jQuery(this).addClass("CloseBtn");
				jQuery("#sideBar").animate({
					left: '0'},
					function() {
						
					  });
				jQuery("#iframeGame").animate({
					left: '200',
					width: $(window).width() - 200
				});
				jQuery(this).attr({title : 'Đóng'});
				isClose = false;
			}
		});
	}
	
		/*-----SELECT------*/
	if (jQuery(".SelectUI").length > 0) {      
	   jQuery(".SelectUI").addSelectUI ({
            scrollbarWidth: 7 //default is 10
			
        });
    }
});