@extends('auth.auth')

@section('htmlheader_title')
    Đăng ký
@endsection

@section('add-js')
    <script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
@endsection

@section('content')

    <body class="register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="/"><b>ID</b>SLG</a>
        </div>

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
            <p class="login-box-msg">Đăng ký</p>
            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Tên người dùng" name="name"
                           value="{{ old('name') }}"/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email"
                           value="{{ old('email') }}"/>
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

                <div class="form-group has-feedback">
                    <div class="g-recaptcha" data-sitekey="6LfDGBMTAAAAAMaKjZqtAMvD7yjHIrj3h16kShZO"></div>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Tôi đồng ý với mọi <a target="_blank"
                                                                              href="http://slg.vn/policy">điều khoản của
                                    SLG</a>
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Đăng ký</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <div class="social-auth-links text-center">
                <p>- Hoặc -</p>
                <a target="_blank" href="{{ route('auth.getSocialAuth', ['provider' => 'facebook']) }}"
                   class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Đăng ký qua
                    Facebook</a>
                <a target="_blank" href="{{ route('auth.getSocialAuth', ['provider' => 'google']) }}"
                   class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Đăng ký
                    qua Google+</a>
            </div>

            <a href="{{ route('login') }}" class="text-center">Đăng nhập</a>
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
