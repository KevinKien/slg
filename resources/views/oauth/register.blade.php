@extends('auth.auth')

@section('htmlheader_title')
    Đăng ký
@endsection

@section('add-css')
    <link href="{{ captcha_layout_stylesheet_url() }}" type="text/css" rel="stylesheet">
@endsection

@section('content')

    <body class="register-page" style="background: transparent;">
    <div class="register-box" style="margin-top: 0">
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

        <div class="register-box-body">
            <div class="register-logo">
                Đăng ký
            </div>
            {!! Form::open(['method' => 'POST', 'url'=> route('oauth.register.post',$params)]) !!}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Tên người dùng" name="name"
                       value="{{ old('name') }}"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Mật khẩu" name="password"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu"
                       name="password_confirmation"/>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>

            <div class="form-group">
                {!! captcha_image_html('RegisterCaptcha') !!}
            </div>

            <div class="form-group has-feedback">
                <input id="CaptchaCode" type="text" name="CaptchaCode" class="form-control" placeholder="Captcha" autocomplete="0"/>
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Tôi đồng ý với mọi <a target="_blank" href="http://slg.vn/policy">điều
                                khoản của SLG.</a>
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Đăng ký</button>
                </div><!-- /.col -->
            </div>
            {!! Form::close() !!}
            <div class="social-auth-links text-center">
                <p>- Hoặc -</p>
                <a target="_blank" href="http://id.slg.vn/auth/login/facebook"
                   class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Đăng ký qua
                    Facebook</a>
                <a target="_blank" href="http://id.slg.vn/auth/login/google"
                   class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Đăng ký
                    qua Google+</a>
            </div>

            <a href="{{ route('oauth.authorize.post',$params) }}" class="text-center">Đăng nhập</a>
        </div><!-- /.form-box -->
    </div><!-- /.register-box -->

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
