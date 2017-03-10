<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>

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
    <div style="background:#f7f7f7;padding:50px;font-family:Arial,Helvetica,sans-serif;font-size:12px">
        <table cellspacing="0" cellpadding="0" style="width:640px;border-collapse:collapse;border:solid 1px #ccc;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px">
            <tbody>
            <tr>
                <td style="background:#db6e24;border-top:1px solid #bababa;border-bottom:2px solid #dbd7cb;height:4px">
                </td>
            </tr>
            <tr>
                <td style="width:100%;background:#fff;padding:20px 25px">
                    <div style="margin:0;line-height:17px;padding-bottom:10px">
                        Chào<strong>
                            Bạn</strong>
                        ,</div>
                        {{--<a target="_blank" href="{{ $linkcms }}" style="display:block;font-size:11px;vertical-align:middle;color:#000000;text-decoration:none">--}}
                           {{----}}
                        {{--</a>--}}
                        <?php print_r($content) ?>
                    <div style="padding-top:20px;line-height:17px">
                        Thân ái</div>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
</div>
</body>
</html>