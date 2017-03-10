@extends('auth.auth')

@section('htmlheader_title')
    Khôi phục mật khẩu
@endsection

@section('add-js')
    <script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
@endsection

@section('content')

<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>ID</b>SLG</a>
        </div><!-- /.login-logo -->

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Có lỗi xảy ra với thông tin nhập vào của bạn.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="login-box-body">
            <p class="login-box-msg">Khôi phục mật khẩu</p>
            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <div class="g-recaptcha" data-sitekey="6LfDGBMTAAAAAMaKjZqtAMvD7yjHIrj3h16kShZO"></div>
                </div>

                <div class="row">
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Gửi yêu cầu</button>
                    </div><!-- /.col -->
                    <div class="col-xs-2">
                    </div><!-- /.col -->
                </div>
            </form>

            <a href="{{ route('login') }}">Đăng nhập</a><br>
            <a href="{{ route('register') }}" class="text-center">Đăng ký</a>

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

@endsection
