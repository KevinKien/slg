<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" href="{{ asset('/game/onepiece/zing/images/favico.ico') }}'" rel="shortcut icon"/>
    <meta name="keywords"
          content="One Piece Online, One Piece,nua hoang hai tac, slg, game hai tac, game nu hoang hai tac, vua hai tac, Vua hải tăc, hải tặc, WebGame One Piece Online hay nhất mọi thời đại , game vui, game hot, game linh vương, webgame mới">
    <title>One Piece Online</title>
   <!-- <link href="{{ asset('/game/onepiece/zing/css/stylez.css') }}" rel="stylesheet" type="text/css" media="all"/>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="{{ asset('/game/onepiece/zing/main/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('/game/onepiece/zing/js/jquery.idTabs.js') }}"></script>
    <link href="{{ asset('/game/onepiece/zing/css/showmessage.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('/game/onepiece/zing/css/mystyle.css') }}" rel="stylesheet">    
    <script src="{{ asset('/game/onepiece/zing/js/showmessage.js') }}"></script>
    <script src="http://static.me.zing.vn/v3/js/zm.xcall-1.22.min.js"></script>
    
    <link href="{{ asset('/game/onepiece/zing/main/css/mycss.css') }}" rel="stylesheet">
    <link href="{{ asset('/game/onepiece/zing/main/css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('/game/onepiece/zing/main/css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('/game/onepiece/zing/main/css/slick-theme.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Maitree" rel="stylesheet">
    <script src="{{ asset('/game/onepiece/zing/main/js/slick.min.js') }}"></script>
    
    <script>
        zmXCall.resizeParent({id: "onepieceonline", height: 860});

        function login() {
            zmXCall.callParent('doLogin');
            zmXCall.callParent('doLogin');
            zmXCall.callParent('doLogin');
        }

        @if (!Session::has('zing_user'))
            login();
        @endif
    </script>
    @yield('additional-header')
</head>