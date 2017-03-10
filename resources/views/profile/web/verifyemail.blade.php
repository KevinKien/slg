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
        <div class="col-md-8" >
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Thay đổi thông tin người dùng</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">       
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="Verify">Verify</label>
                                    <input type="text" class="form-control" id="fullname" placeholder="Verify" name="verify" >
                                    @foreach ($errors->get('verify') as $message)
                                        <p style="color:red;padding-top: 10px">{!!$message!!}</p>
                                    @endforeach
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
</section>
@endsection

