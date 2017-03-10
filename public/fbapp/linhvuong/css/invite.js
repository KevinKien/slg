/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
    
        window.fbAsyncInit = function () {
            FB.init({
                appId: '509286652582871',
                xfbml: true,
                version: 'v2.5'
            });
        };
    
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    
        function invite() {
            FB.ui({
                method: "apprequests",
                message: "Check out my great app"
            }, inviteCallback);
        }
    
        function inviteCallback(response) {
            console.log(response);
        }    

//----------------------------------------------------------

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

    $(function () {
        $('#banner').cycle({
            fx: 'fade',
            pager: '#btn'
        });
    })

    $(document).ready(function () {
        $('#slider1').tinycarousel();
    });
