if (document.getElementById) {
    window.alert = function (txt) {
        createCustomAlert(txt);
    }
    window.confirm = function (txt, callback, parameter) {
        if (callback != null)
            createCustomConfirm(txt, callback, parameter);
    }
}
var ALERT_TITLE = "TỨ HOÀNG ĐẠI CHIẾN";
var ALERT_BUTTON_ACCEPT_TEXT = "Đồng ý";
var ALERT_BUTTON_CANCEL_TEXT = "Hủy";

function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}


function createCustomAlert(txt) {
    var txt = decodeEntities(txt);

    d = document;

    if (d.getElementById("modalContainer")) return;

    mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
    mObj.id = "modalContainer";
    mObj.style.height = d.documentElement.scrollHeight + "px";

    alertObj = mObj.appendChild(d.createElement("div"));
    alertObj.id = "alertBox";
    if (d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
    alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth) / 2 + "px";
    alertObj.style.visiblity = "visible";

    h1 = alertObj.appendChild(d.createElement("h1"));
    h1.appendChild(d.createTextNode(ALERT_TITLE));

    msg = alertObj.appendChild(d.createElement("p"));
    msg.id = "Content";
    msg.appendChild(d.createTextNode(txt));
    // msg.innerHTML = txt;

    btn = alertObj.appendChild(d.createElement("a"));
    btn.id = "closeBtn";
    btn.class = "btn blue ";
    btn.appendChild(d.createTextNode(ALERT_BUTTON_ACCEPT_TEXT));
    btn.href = "#";
    btn.focus();
    btn.onclick = function () { removeCustomAlert(); return false; }

    alertObj.style.display = "block";

}



function createCustomConfirm(txt, callback, parameter) {
    d = document;

    if (d.getElementById("modalContainer")) return;

    mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
    mObj.id = "modalContainer";
    mObj.style.height = d.documentElement.scrollHeight + "px";

    alertObj = mObj.appendChild(d.createElement("div"));
    alertObj.id = "alertBox";
    if (d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
    alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth) / 2 + "px";
    alertObj.style.visiblity = "visible";

    h1 = alertObj.appendChild(d.createElement("h1"));
    h1.appendChild(d.createTextNode(ALERT_TITLE));

    msg = alertObj.appendChild(d.createElement("p"));
    msg.id = "Content";
    msg.appendChild(d.createTextNode(txt));
     document.getElementById("Content").style.marginTop="10px";


    alertObj1 = alertObj.appendChild(d.createElement("div"));
    alertObj1.id = "alertBox2";
    if (d.all && !window.opera) alertObj1.style.top = document.documentElement.scrollTop + "px";
    alertObj1.style.left = (d.documentElement.scrollWidth - alertObj1.offsetWidth) / 2 + "px";
    alertObj1.style.visiblity = "visible";

    // create an anchor element to use as the confirmation button.
    btn1 = alertObj1.appendChild(d.createElement("a"));
    btn1.id = "closeBtn";
    btn1.appendChild(d.createTextNode(ALERT_BUTTON_ACCEPT_TEXT));
    btn1.href = "#";


    btn2 = alertObj1.appendChild(d.createElement("a"));
    btn2.id = "cancelBtn";
    btn2.appendChild(d.createTextNode(ALERT_BUTTON_CANCEL_TEXT));
    btn2.href = "#";
    btn2.class = "btn ";

    $("#closeBtn").focus().select();
    $("#closeBtn, #cancelBtn").keypress(function (e) {
        if (e.keyCode == 13) $("#closeBtn").trigger('click');
        if (e.keyCode == 27) $("#cancelBtn").trigger('click');
    });

    try {
        $("#alertBox").draggable({ handle: $("#popup_title") });
        $("#popup_title").css({ cursor: 'move' });
    } catch (e) { /* requires jQuery UI draggables */ }

    // set up the onclick event to remove the alert when the anchor is clicked
    btn1.onclick = function () { removeCustomAlert(); if (callback) callback(true, parameter); }
    btn2.onclick = function () { removeCustomAlert(); if (callback) callback(false, parameter); }
    alertObj.style.display = "block";
}



function removeCustomAlert() {
    document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
}

