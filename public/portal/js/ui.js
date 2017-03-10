

// layer popup
var fm_all = null;
var div = null;
var imgdiv = null;
var fm = null;

function coverScreen(strDivSrc) {
    var h = parseInt(document.documentElement.scrollHeight) + "px";
    var w = parseInt(document.body.offsetWidth) + "px";

    div = document.createElement("iframe");
    div.className = "dvclass";
    div.style.top = "0px";
    div.style.left = "0px";
    div.style.width = w;
    div.style.height = h;
    div.style.position = "absolute";
    div.style.zIndex = 9990;
    div.style.display = "none";
    div.frameBorder = "0";
    div.style.margin = "0";
    div.src = strDivSrc;

    document.body.appendChild(div);

    document.body.onresize = document.body.onresizestart = document.body.onresizeend = document.body.onscroll = function () {
        div.style.top = "0px";
        div.style.left = "0px";
        div.style.width = parseInt(document.body.offsetWidth) + 'px';
        div.style.height = parseInt(document.documentElement.scrollHeight) + "px";
        imgdiv.style.left = (parseInt(document.documentElement.clientWidth) / 2) - (parseInt(imgdiv.style.width) / 2) + 'px';
        //imgdiv.style.top = ( parseInt( document.body.scrollHeight ) / 2 ) - ( parseInt( imgdiv.style.height ) / 2 ) + 'px';
        //imgdiv.style.top = ( parseInt( document.documentElement.clientHeight ) / 2 ) - ( parseInt( imgdiv.style.height ) / 2 ) + 'px';
    }

    imgdiv = document.createElement("div");
    imgdiv.style.width = 400;
    imgdiv.style.height = 400;
    imgdiv.style.left = (parseInt(div.style.width) / 2) - (parseInt(imgdiv.style.width) / 2) + 'px';
    //imgdiv.style.top = ( parseInt( document.body.scrollHeight ) / 2 ) - ( parseInt( imgdiv.style.height ) / 2 ) + 'px';
    imgdiv.style.top = (parseInt(document.documentElement.clientHeight) / 2) - (parseInt(imgdiv.style.height) / 2) + 'px';
    imgdiv.style.position = "absolute";
    imgdiv.style.zIndex = 9999;
    imgdiv.style.display = "none";
    imgdiv.style.backgroundColor = "transparent";
    imgdiv.style.color = "#FFFFFF";
    document.body.appendChild(imgdiv);

    fm = document.createElement("iframe");
    fm.id = "iframe_pop";
    fm.style.width = "100%";
    fm.style.height = "100%";
    fm.allowTransparency = "true";
    fm.style.backgroundColor = "transparent";
    fm.frameBorder = "0";
    fm.style.margin = "0";
    imgdiv.appendChild(fm);

}

function showImg(_w, _h, _src) {
    if (div == null) {
        coverScreen("http://static.webzen.com/platform/ob/common/v1/js/back.html");
    }
    imgdiv.style.width = _w + 'px';
    imgdiv.style.height = _h + 'px';
    //imgdiv.style.left = ( parseInt( document.documentElement.clientWidth ) / 2 ) - ( parseInt( imgdiv.style.width ) / 2 ) + 'px';
    imgdiv.style.left = (parseInt(document.documentElement.clientWidth) / 2) - (parseInt(imgdiv.style.width) / 2) + 'px';
    imgdiv.style.top = ((parseInt(document.documentElement.clientHeight) / 2) + (parseInt(document.body.scrollTop + document.documentElement.scrollTop))) - (parseInt(_h) / 2) + 'px';
    //imgdiv.style.top = ( parseInt( div.style.height ) / 2 ) - ( parseInt( imgdiv.style.height ) / 2 ) + ( parseInt( document.documentElement.scrollTop ) / 2 )  + 'px';
    fm.src = _src;
    div.style.display = "block";
    imgdiv.style.display = "block";
    //	div.document.body.onclick = function(){
    //		hideImg();
    //	}

}

function hideImg() {
    div.style.display = "none";
    imgdiv.style.display = "none";
    fm.src = "";
}
/* s:member */
/* login input action */
$(function () {
	var placeholder_input = $(".input_div_wrp input:first-child");
	placeholder_input.bind({
		focusin : function(){
			var _this = $(this);
			_this.addClass("Input_has_focus");
			_this.parent().find(".input_info_message").show();
		},
		focusout: function(){
			var _this = $(this);
			_this.removeClass("Input_has_focus");
			_this.parent().find(".input_info_message").hide();
		},
		keyup: function(){
			var _this = $(this);
			if (_this.val().length==0){
				_this.parent().find(".input_label").show();
			} else {
				_this.parent().find(".input_label").hide();
			}
		}
	}).not("[value='']").each(function(idx) {
		var _this = $(this);
		_this.addClass("Input_has_focus");
		_this.parent().find(".input_label").hide();
		_this.parent().find(".input_info_message").show();
	});
});
/* e:member */

/* s:cs view show more */
$( function() {
	if ($(".viewEditArea").length > 0 && $(".viewEditArea").height() >= $(".viewEditArea").css("max-height").replace(/px/, "")) {
		$(".btn_cs_show").show().click(function() {
			$(".viewEditArea").css("max-height", "100%");
			$(".btn_cs_show").css("display", "none");
		});
	}
});
/* e:cs view show more */
