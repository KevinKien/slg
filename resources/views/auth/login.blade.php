@extends('auth.auth')

@section('htmlheader_title', 'Đăng nhập')

@section('content')
    <body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>ID</b>SLG</a>
        </div><!-- /.login-logo -->

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
            <p class="login-box-msg">Đăng nhập</p>
            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Email hoặc Tên người dùng" name="email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember_me"> Ghi nhớ đăng nhập.
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-5">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Đăng nhập</button>
                    </div><!-- /.col -->
                </div>
            </form>
            <div class="social-auth-links text-center">
                <p>- Hoặc -</p>
                <a href="{{ route('auth.getSocialAuth', ['provider' => 'facebook']) }}" class="btn btn-block btn-social btn-facebook btn-flat"><i
                            class="fa fa-facebook"></i> Đăng nhập qua Facebook</a>
                <a href="{{ route('auth.getSocialAuth', ['provider' => 'google']) }}" class="btn btn-block btn-social btn-google-plus btn-flat"><i
                            class="fa fa-google-plus"></i> Đăng nhập qua Google+</a>
            </div><!-- /.social-auth-links -->

            <a href="{{ secure_url('/password/email') }}">Quên mật khẩu</a><br>
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
