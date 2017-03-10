var folder_name = 'client-linhkiem';
var root_path = 'http://soap.soha.vn/dialog/';//Domain cua soap, khong thay doi
var APP_KEY = '7d2b8a2dd6fffe14477b0cafb77abb89';//app_key do SOAP cung cap
var client_path = 'soap/request.php';
var callback = 'https://fpay.vn/payment/sohapayment/manga';
var pay_redirect = 'https://fpay.vn/payment/sohapayment/payredirect';
var callback_post_feed = 'http://127.0.0.1/' + folder_name + '/callback_post_feed.php';
var callback_request_feed = 'http://127.0.0.1/' + folder_name + '/callback_request_feed.php';

function getParams(args) {
    params = "?";
    for (i in args) {
        params += (i + '=' + args[i] + '&');
    }
    return params;
}


function openPostFeedWindow() {
    var _width = 800;
    var Xpos = ((screen.availWidth - _width) / 2);
    var _height = 510;
    var Ypos = ((screen.availHeight - _height) / 2);
    var caption = "caption";
    var description = "description";
    var message = "message";
    var image = "http://my.soha.vn/templates/images/logo.png";
    var link = "link";

    path = root_path + 'dialog/feed?oauth_consumer_key=' + APP_KEY + '&oauth_callback=' + callback_post_feed + '&caption=' + caption + '&description=' + description + '&message=' + message + '&picture=' + image + '&link=' + link;
    window.open(path, '', 'width=' + _width + ',height=' + _height + ',toolbar=no,resizable=fixed,status=no,scrollbars=no,menubar=no,screenX=' + Xpos + ',screenY=' + Ypos);
}

function openRequestFeedWindow() {
    var _width = 800;
    var Xpos = ((screen.availWidth - _width) / 2);
    var _height = 600;
    var Ypos = ((screen.availHeight - _height) / 2);
    var caption = "caption";
    var description = "description";
    var message = "message";
    var image = "http://my.soha.vn/templates/images/logo.png";
    var link = "link_toi_game";
    path = root_path + 'dialog/request?oauth_consumer_key=' + APP_KEY + '&oauth_callback=' + callback_request_feed + '&caption=' + caption + '&description=' + description + '&message=' + message + '&picture=' + image;
    window.open(path, '', 'width=' + _width + ',height=' + _height + ',toolbar=no,resizable=fixed,status=no,scrollbars=no,menubar=no,screenX=' + Xpos + ',screenY=' + Ypos);
}

function openPaymentWindows(order_info, serverid, userid) {
    var _width = 800;
    var Xpos = ((screen.availWidth - _width) / 2);
    var _height = 510;
    var Ypos = ((screen.availHeight - _height) / 2);

    path = 'http://linhvuong.sohaplay.vn/redirectsohaform?order_info=' + order_info;
    path += '&server_id=' + serverid + '&userid=' + userid;
    //path = 'http://soap.soha.vn/dialog/pay?app_id=' + APP_KEY + '&redirect_uri=' + pay_redirect + '&order_info=' + order_info;
    window.open(path, '', 'width=' + _width + ',height=' + _height + ',toolbar=no,resizable=fixed,status=no,scrollbars=no,menubar=no,screenX=' + Xpos + ',screenY=' + Ypos);
}

