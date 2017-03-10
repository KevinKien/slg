var servername = top.location.host;
//alert(servername);
if (servername == 'gam386.com') {
    var slg_app_key = '223f8755dd66c09440d610ef3311dd70';
} else if (servername == 'box4game.com' || servername == 'box4game.net' || servername == 'mongdevuong.com') {
    var slg_app_key = '452270077ca8ad3b061863ed42e01101';
} else if (servername == 'game.cgo.com') {
    var slg_app_key = 'ae22d87dc91ca7a583d3949eb93052b0';
} else if (servername == 'trutien.box4game.net') {
    var slg_app_key = 'dbc42222b855e1f0fa0f1c33c549cbbf';
}

//var slg_register_callback = 'http://' + servername + '/request';
var slg_register_callback = 'http://' + servername + '/redirect';
var slg_client_path = 'http://' + servername + '/request';
var logout_url = 'http://' + servername + '/slg_logout';
var login_url = 'http://' + servername + '/slg_login';
var regis_url = 'http://' + servername + '/slg_register';
var slg_callback_play = 'http://' + servername + '/slg_callback';

var $ = jQuery.noConflict();
//var slg_d = new Date();var slg_v = slg_d.getTime();
$(function () {
    //$.getScript("http://id.cgo.vn/login/checksessionTemp?fnc=slgmidCallback&v="+slg_v);	
})
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

function connectBig4(type) {

    type = typeof type !== 'undefined' ? type : 2;

    var slg_connectBig4_url = 'http://soap.soha.vn/dialog/ConnectBig4';
    slg_connectBig4_url = slg_connectBig4_url + '?type=' + type + '&app_key=' + slg_app_key + '&callback_url=' + slg_client_path;
    //slg_openMyModal(slg_connectBig4_url, 600, 600);
    var _width = 693;
    var Xpos = ((screen.availWidth - _width) / 2);
    var _height = 429;
    var Ypos = ((screen.availHeight - _height) / 2);
    window.mingWindow = window.open(slg_connectBig4_url, '', 'width=' + _width + ',height=' + _height + ',toolbar=no,resizable=fixed,status=no,scrollbars=no,menubar=no,screenX=' + Xpos + ',screenY=' + Ypos);
    mingWindow.focus();
    return false;
}

/* function slg_register() {		
 var url_register = "http://id.cgo.vn/OauthServerV2/register?app_key=d9c694bd04eb35d96f1d71a84141d075&clearsession=1&confirm=1";	
 url_register = url_register+'&callback_register='+slg_register_callback;
 slg_openMyModal(url_register, 600, 600);
 } */

function slg_register() {
    var url_register = regis_url;
    slg_openMyModal(url_register, 340, 600);
}

function slg_logout() {
    slg_openMyModal(logout_url, 5, 5);
//	location.href = logout_url;
}

function slg_safari_detect() {
    safari_detect = 'http://thanhchien.soha.vn/index.php?option=com_loginming&view=safaridetect&tmpl=component';
    slg_openMyModal(safari_detect, 540, 113);
}


function mingAuthCallBack(data) {
    var rels = eval('(' + data + ')');
    if (rels != null) {
        ming_id_raw = rels.id;
        ming_username_raw = rels.username;
        ming_user = 0;
        if ($.cookie("rels")) {
            ming_user = $.parseJSON($.cookie("rels"));
        }

        if (ming_user && (ming_user.id != null) && (ming_user.id == ming_id_raw)) {
            ////alert('giong');
        } else if (ming_id_raw != null) {
            $.cookie("rels", $.toJSON(rels), {expires: 1});
            location.href = location.href;
        }

    }/* else{
     if($.cookie("rels") != 0){
     $.cookie("rels", 0,{ expires: 1 });
     //location.href = location.href;
     }
     } */
}

var client_path = 'http://pay.cgo.vn/topupcash';

function openTopupCashWindow() {
    var _width = 693;
    var Xpos = ((screen.availWidth - _width) / 2);
    var _height = 800;
    var Ypos = ((screen.availHeight - _height) / 2);
    slg_openMyModal(client_path, 540, 800);
    return false;
}

function slg_isDoneCheckingSoap(rels) {
    if (slg_done_check_soap == 1) {
        window.clearInterval(slg_int_checking_soap);
        if (slg_has_login_soap == 0) {
            //chua login soap
            slgDoLogin(rels);
        }
    }
}

function slgshCallback(data) {
    var rels = eval('(' + data + ')');
    if (rels != null) {
        slg_has_login_soap = 1;
        id = rels.id;
        //alert('LOGIN MING ID--2');
        username = rels.username;
        email = rels.email;
        status = rels.status;
        join_time = rels.join_time;
        slgDoLogin(rels);
    } else {
        //CHUA LOGIN SOAP
        //alert('CHUA LOGIN MING ID 2');
    }
    slg_done_check_soap = 1;
}

function slgmidCallback(data) {
    var rels = eval('(' + data + ')');
    if (rels != null) {
        id = rels.id;
        //alert('LOGIN MING ID --1');
        username = rels.username;
        email = rels.email;
        status = rels.status;
        join_time = rels.join_time;
        if (slg_done_check_soap == 0) {
            //chua check xong soap
            slg_int_checking_soap = window.setInterval(slg_isDoneCheckingSoap(rels), 100000);
        } else {
            if (slg_has_login_soap == 0) {
                //chua login soap
                slgDoLogin(rels);
            }
        }
    } else {
        //alert('CHUA LOGIN MING ID --1');
    }
}

function DoTheRefresh()
{
    //alert('poupou');
    location.reload();
}