<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SLG') | Solo Game</title>
    <link href="{{ asset('/frontend/css/bootstrap.min.css', true) }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/frontend/css/font-awesome.min.css', true) }}">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic&amp;subset=latin,vietnamese"
          rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('/frontend/css/style.css', true) }}">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{ asset('/frontend/js/bootstrap.min.js', true) }}"></script>
    @yield('additional-css')
</head>