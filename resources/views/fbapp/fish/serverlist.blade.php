<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hải tặc tân vương</title>
    <link href="https://app.slg.vn/fbapp/fish/css/mystyle.css" rel="stylesheet">
  </head>
  <body>    
    <header>
        <div style=" width: 100%; float: left; margin-bottom: 12px; color: #000;">
            @if(is_object(Auth::user()) &&  Auth::user()->name )
            <span>Xin chào, <b>{{ !empty(Auth::user()->fullname) ? Auth::user()->fullname : Auth::user()->name }}</b></span>
            @else
            <span>Xin chào, <b>Guest</b></span>
            @endif
        </div>
        <div style=" width: 100%; float: left; margin-bottom: 12px; color: white; margin-top: 0px;">
                <a href="#" class="card"></a>                       
                <div class="fb-like" data-href="https://apps.facebook.com/1957592584466475" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
         <a href="#" class="iphone_screen"></a>    
        <div class="menu">
            <ul>
                <li><a href="http://tieulong.slg.vn/" target="_blank" style="background-image: url(https://app.slg.vn/fbapp/fish/imgs/menu_03.png);">Trang chủ</a></li>
                <li><a href="#" onclick="invite();" class="invite" style="background-image: url(https://app.slg.vn/fbapp/fish/imgs/menu_05.png);">Invite</a></li>
                <li><a href="http://tieulong.slg.vn/download" target="_blank" style="background-image: url(https://app.slg.vn/fbapp/fish/imgs/menu_07.png);">Dowload</a></li>
                <li><a href="http://id.slg.vn/profile" target="_blank" style="background-image: url(https://app.slg.vn/fbapp/fish/imgs/menu_09.png);">Tài khoản</a></li>
            </ul>
        </div>
		
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link rel="apple-touch-icon" href="https://app.slg.vn/fbapp/fish/images/icon.png"/>
		<link rel="apple-touch-startup-image" href="https://app.slg.vn/fbapp/fish/images/icon.png" />

		<style type="text/css">
			body, div, canvas
			{
				image-rendering: optimizeSpeed;
				-webkit-image-rendering: optimizeSpeed;
				-webkit-interpolation-mode: nearest-neighbor;
			}
			body{padding:0; margin:0;font-size:12px;background-color:#000;}
			body, html{height: 100%;}
			#outer{height:100%; overflow:hidden; position:relative; width:100%;}
			#outer[id]{display:table; position:static;}
			#middle{position:absolute; top:50%;} /* for ie only*/
			#middle[id]{display:table-cell; vertical-align:middle; position:static;}                   
			
		</style>
	 
		<title>@yield('title', 'SLG') | SLG</title>

		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/js/quark.base-1.0.0.alpha.min.js"></script>
		<!--<script type="text/javascript" src="fishjoy.game.js"></script>-->
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/R.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/Utils.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/fishjoy.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/FishManager.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/FishGroup.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/Fish.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/Cannon.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/Bullet.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/Num.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/Player.js"></script>
		<script type="text/javascript" src="https://app.slg.vn/fbapp/fish/src/invite.js"></script>
		
		
    </header>
     
    <div class="game">
								
                
        <div id="outer">
                <div id="middle">        
                        <div id="container" style="position:relative;width:980px;height:545px;top:-50%;margin:0 auto;"></div>
                        <div id="msg"></div>
                </div>
        </div>
                <div id="fps" style="position:absolute;top:0;left:0;color:#000; margin-top: 26px; margin-left: 150px;">        
                </div>


    </div>

    <footer>
        <div class="footer_button">
            <a href="http://tieulong.slg.vn/download" target="_blank" class="google"></a>
            <a href="http://tieulong.slg.vn/download" target="_blank" class="logo"></a>
            <a href="http://tieulong.slg.vn/download" target="_blank" class="apple"></a>
        </div>
        <p>Độc quyền phát hành tại Việt nam bởi SLG<Br>
            Hỗ trợ: hotro@slg.vn<Br>
            Tiểu Long Du Hý - Game giải trí hàng đầu</p>

    </footer>
  </body>
</html>