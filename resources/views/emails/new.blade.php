<html>
<head>
    <meta charset="utf-8">
    <title>Chào mừng bạn đến với SLG</title>

    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container">
    Chào mừng <strong>{{ $fullname }},</strong><br>
    <br>
    Cảm ơn bạn đã đăng ký tài khoản tại SLG.vn. Thông tin đăng nhập SLG của bạn là:<br>
    <br>
        - Tài khoản: {{ $name }}<br>
        - Mật khẩu: {{ $password }}<br>
    <br>
</div>
</body>
</html>