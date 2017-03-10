@extends('app')
@section('contentheader_title', 'Change password')
@section('css-current')
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
@endsection
@section('main-content')

<!--===================================-->

<div class="box">
    @foreach ($errors->all() as $error)
    <div class="callout callout-danger">
        <h4>Error!</h4>
        <p>{{ $error }}</p>
    </div>
    @endforeach
    @if(Session::has('success'))
    <div class="callout callout-success">
        <h4>Success!</h4>
        <p>{{ Session::get('success') }}</p>
    </div>
    @endif
    <div class="row">
        <div class="col-md-4" >
            <div class="box  box-info profile_bar">
                <div class="box-header with-border">
                    <h3 class="box-title">Profile</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <ul class="profile_menu">

                        <li >
                            <a href="//id.slg.vn/profile">
                                <div class="info-box">
                                    <span class="info-box-icon "><i class="fa fa-fw fa-user color_dn"></i></span>
                                    <div class="info-box-content">
                                        <h3 class="info-box-text">Thông tin đăng nhập</h3>
                                        <span class="progress-description">Quản lý thông tin dùng để đăng nhập</span>
                                    </div><!-- /.info-box-content -->
                                </div>
                            </a>
                        </li>
                        <li >
                            <a href="//id.slg.vn/personal">
                                <div class="info-box">
                                    <span class="info-box-icon "><i class="fa fa-fw fa-align-justify color_tc"></i></span>
                                    <div class="info-box-content">
                                        <h3 class="info-box-text">Thông tin chung</h3>
                                        <span class="progress-description">Xem và cập nhật thông tin cá nhân</span>
                                    </div><!-- /.info-box-content -->
                                </div>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-md-8" >
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Change password</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">       
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="password_old">Oldpassword</label>
                                    <input type="password" class="form-control" id="password_old" placeholder="Enter old password" name="password_old" >
                                </div>
                                <div class="form-group">
                                    <label for="InputPassword">Password</label>
                                    <input type="password" class="form-control" id="InputPassword" placeholder="Password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="Inputpassword_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="Inputpassword_confirmation" placeholder="Confirm Password" name="password_confirmation">
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
            </div>
    </div>
</div> 
@endsection
