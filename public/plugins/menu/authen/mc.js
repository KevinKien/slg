var servername = top.location.host;

var logout_url = 'http://' + servername + '/demo/authen/logout.php';
var login_url = 'http://' + servername + '/demo/authen/login.php';
var regis_url = 'http://' + servername + '/demo/authen/register.php';
var slg_callback_play = 'http://' + servername + '/demo/authen/callback.php';

var $ = jQuery.noConflict();

var slg_modalWindow = {
    parent: "body",
    windowId: null,
    content: null,
    width: null,
    height: null,
    close: function () {
        $(".modal-window").remove();
        $(".modal-overlay").remove();
    },
    open: function () {
        var modal = "";
        modal += "<div class=\"modal-overlay\"></div>";
        modal += "<div id=\"" + this.windowId + "\" class=\"modal-window\" style=\"width:" + this.width + "px; height:" + this.height + "px; margin-top:-" + (this.height / 3) + "px; margin-left:-" + (this.width / 2) + "px;\">";
        modal += this.content;
        modal += "</div>";
        $(this.parent).append(modal);
        $(".modal-window").append("<a class=\"close-window\"></a>");
        $(".close-window").click(function () {
            slg_modalWindow.close();
        });
    }
};

function slg_openMyModal(source, width, height) {
    slg_modalWindow.windowId = "myModal";
    slg_modalWindow.width = width;
    slg_modalWindow.height = height;
    slg_modalWindow.content = "<iframe frameborder='0' scrolling='no' allowtransparency='true' src='" + source + "' style='width:100%;height:100%'></iframe>";
    slg_modalWindow.open();
}

function closeMyModal() {
    slg_modalWindow.close();
    window.location.href = window.location.href;
}

function slg_check_loginming() {
    slg_openMyModal(login_url, 340, 450);
}

function slg_regisform(url) {
    //slg_client_path = slg_client_path+'?ref=2&callback_register='+slg_register_callback;
    //slg_openMyModal(slg_client_path, 600, 600);
    slg_openMyModal(url, 380, 600);
}

function slg_register() {
    var url_register = regis_url;
    slg_openMyModal(url_register, 340, 600);
}

function slg_logout() {
    slg_openMyModal(logout_url, 5, 5);
//	location.href = logout_url;
}



var client_path = 'http://pay.slg.vn/topupcash';

function openTopupCashWindow() {
    var _width = 693;
    var Xpos = ((screen.availWidth - _width) / 2);
    var _height = 800;
    var Ypos = ((screen.availHeight - _height) / 2);
    slg_openMyModal(client_path, 540, 800);
    return false;
}
