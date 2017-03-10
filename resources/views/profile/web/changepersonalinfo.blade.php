@extends('layout.newprofileweb.layout')
@section('css-current')
    <!-- Bootstrap 3.3.4 -->
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
    <link href="//id.slg.vn/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <style>
        .select_control{
            width: 33.33%!important;
            display: inline-block;
        }
    </style>
@endsection
@section('htmlheader_title')
Thay đổi thông tin user
@endsection
@section('content')
<section id="ContentWrap">
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
            @include('profile.web.profilebar')
        </div>
        <div class="col-md-8" style="margin-bottom: 20px" >
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Thay đổi thông tin người dùng</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">       
                            <div class="box-body">
                                <div class="form-group col-sm-11" style="margin-top: 20px">
                                    <label for="Fullname" class="col-sm-4 control-label">Họ tên</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="fullname" placeholder="Họ tên" name="fullname" value="{{isset(Auth::user()->fullname)?Auth::user()->fullname:''}}" >
                                    </div>
                                    @foreach ($errors->get('fullname') as $message)
                                        <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                    @endforeach
                                </div>
                                <div class="form-group col-sm-11">
                                    <label for="Birthday" class="col-sm-4 control-label">Ngày sinh</label>
                                    <div class="col-sm-8">
                                        <div id="birthday"></div>
                                    </div>
                                    <!--<input type="text" class="form-control" id="birthday" placeholder="Ngày sinh" name="birthday" value="{{isset(Auth::user()->birthday)?date('d-m-Y',strtotime(Auth::user()->birthday)):''}}" >-->
                                </div>
                                <div class="form-group col-sm-11">
                                    <label for="Sex" class="col-sm-4 control-label">Giới tính</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="sex"  name="sex">
                                            <option value="1">Nam</option>
                                            <option value="0" <?php  if( Auth::user()->sex == 0){echo "selected" ; } ?> >Nữ</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group col-sm-11">
                                    <label for="Address" class="col-sm-4 control-label">Địa chỉ</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="addess" placeholder="Địa chỉ" name="address" value="{{isset(Auth::user()->address)?Auth::user()->address:''}}" >
                                    </div>
                                </div>
                                <?php if(empty(Auth::user()->email)){ ?>
                                <div class="form-group col-sm-11">
                                    <label for="Email" class="col-sm-4 control-label">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" >
                                        @foreach ($errors->get('email') as $message)
                                            <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                        @endforeach
                                        <p style="color:red;font-weight: bold; padding: 10px 0;">Chú ý: Email đã nhập không thể chỉnh sửa</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(empty(Auth::user()->phone)){ ?>
                                <div class="form-group col-sm-11">
                                    <label for="Phone" class="col-sm-4 control-label">Số điện thoại</label>
                                    <div class="col-sm-8">
                                        <input pattern=".{0}|.{10,}" title="Ít nhất 10 ký tự" type="tel" class="form-control" id="phone" placeholder="Số điện thoại" name="phone" >
                                        @foreach ($errors->get('phone') as $message)
                                            <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                        @endforeach
                                        <p style="color:red;font-weight: bold; padding: 10px 0;">Chú ý: Số điện thoại đã nhập không thể chỉnh sửa</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(empty(Auth::user()->identify)){ ?>
                                <div class="form-group col-sm-11">
                                    <label for="Identify" class="col-sm-4 control-label">Số chứng minh</label>
                                    <div class="col-sm-8">
                                        <input pattern=".{0}|.{9,}" title="Ít nhất 9 ký tự"  type="text" class="form-control" id="identify" placeholder="Số chứng minh" name="identify" >
                                        @foreach ($errors->get('identify') as $message)
                                            <p style="color:red; padding-top: 10px">{!!$message!!}</p>
                                        @endforeach
                                        <p style="color:red;font-weight: bold; padding: 10px 0;">Chú ý: Số chứng minh đã nhập không thể chỉnh sửa</p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div><!-- /.box-body -->

                            <div class="box-footer col-sm-11">
                                <button type="submit" class="btn btn-primary col-sm-4">Submit</button>
                            </div>
                        </form>
                    </div>
            </div>
    </div>
</div>
</section>
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
