@extends('app')
@section('contentheader_title', 'Change password')
@section('css-current')
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
    <link href="//id.slg.vn/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <style>
        .select_control{
            width: 33.33%!important;
            display: inline-block;
        }
    </style>
@endsection
@section('main-content')

<!--===================================-->

<div class="box">
    @foreach ($errors->all() as $error)
<!--    <div class="callout callout-danger">
        <h4>Error!</h4>
        <p>{{ $error }}</p>
    </div>-->
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
                            <h3 class="box-title">THAY ĐỔI THÔNG TIN NGƯỜI DÙNG</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">       
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="Fullname">Họ tên</label>
                                    <input type="text" class="form-control" id="fullname" placeholder="Họ tên" name="fullname" value="{{isset(Auth::user()->fullname)?Auth::user()->fullname:''}}" >
                                    @foreach ($errors->get('fullname') as $message)
                                        <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label for="Birthday">Ngày sinh</label>
                                    <div id="birthday"></div>
                                    <!--<input type="text" class="form-control" id="birthday" placeholder="Ngày sinh" name="birthday" value="{{isset(Auth::user()->birthday)?date('d-m-Y',strtotime(Auth::user()->birthday)):''}}" >-->
                                </div>
                                <div class="form-group">
                                    <label for="Sex">Giới tính</label>
                                    <select class="form-control" id="sex"  name="sex">
                                        <option value="1">Nam</option>
                                        <option value="0" <?php  if( Auth::user()->sex == 0){echo "selected" ; } ?> >Nữ</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="Address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="addess" placeholder="Địa chỉ" name="address" value="{{isset(Auth::user()->address)?Auth::user()->address:''}}" >
                                </div>
                                <?php if(empty(Auth::user()->email)){ ?>
                                <div class="form-group">
                                    <label for="Email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" >
                                    @foreach ($errors->get('email') as $message)
                                        <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                    @endforeach
                                    <p style="color:red;font-weight: bold; padding: 10px 0;">Chú ý: Email đã nhập không thể chỉnh sửa</p>

                                </div>
                                <?php } ?>
                                <?php if(empty(Auth::user()->phone)){ ?>
                                <div class="form-group">
                                    <label for="Phone">Số điện thoại</label>
                                    <input pattern=".{0}|.{10,}" title="Ít nhất 10 ký tự" type="tel" class="form-control" id="phone" placeholder="Số điện thoại" name="phone" >
                                    @foreach ($errors->get('phone') as $message)
                                        <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                    @endforeach
                                    <p style="color:red;font-weight: bold; padding: 10px 0;">Chú ý: Số điện thoại đã nhập không thể chỉnh sửa</p>
                                </div>
                                <?php } ?>
                                <?php if(empty(Auth::user()->identify)){ ?>
                                <div class="form-group">
                                    <label for="Identify">Số chứng minh</label>
                                    <input pattern=".{0}|.{9,}" title="Ít nhất 9 ký tự"  type="text" class="form-control" id="identify" placeholder="Số chứng minh" name="identify" >
                                    @foreach ($errors->get('identify') as $message)
                                        <p style="color:red; padding-top: 10px">{!!$message!!}</p>
                                    @endforeach
                                    <p style="color:red;font-weight: bold; padding: 10px 0;">Chú ý: Số chứng minh đã nhập không thể chỉnh sửa</p>
                                </div>
                                <?php } ?>
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
@section('js-current')
<script type="text/javascript">

$(function () {
    $('#birthday').birthdayPicker({
        maxAge: 100,
        minAge: 0,
        maxYear: "2014",
        monthFormat: "number",
        placeholder: true,
        defaultDate: "{{isset(Auth::user()->birthday)?date('m-d-Y',strtotime(Auth::user()->birthday)):'false'}}",
        sizeClass: "form-control select_control"
    });

    //Date range picker
//    $('#birthday').datetimepicker({
//            format: 'DD-MM-YYYY',
//            maxDate: moment()
//        });
});

</script>
<script src="{{ asset('/plugins/datetimepicker/jquery-birthday-picker.min.js') }}"  type="text/javascript"></script>
@endsection
