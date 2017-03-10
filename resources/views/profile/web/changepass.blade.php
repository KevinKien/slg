@extends('layout.newprofileweb.layout')
@section('css-current')
    <!-- Bootstrap 3.3.4 -->
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
@endsection
@section('htmlheader_title')
Change password
@endsection
@section('content')
<section id="ContentWrap">
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
            @include('profile.web.profilebar')
        </div>
        <div class="col-md-8" style="margin-bottom: 20px" >
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Change password</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">       
                            <div class="box-body">
                                <div class="form-group col-sm-10" style="margin-top: 20px">
                                    <label for="password_old" class="col-sm-4 control-label">Oldpassword</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password_old" placeholder="Enter old password" name="password_old" >
                                    </div>
                                </div>
                                <div class="form-group col-sm-10">
                                    <label for="InputPassword" class="col-sm-4 control-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="InputPassword" placeholder="Password" name="password">
                                    </div>
                                </div>
                                <div class="form-group col-sm-10">
                                    <label for="Inputpassword_confirmation" class="col-sm-4 control-label">Confirm Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="Inputpassword_confirmation" placeholder="Confirm Password" name="password_confirmation">
                                    </div>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer col-sm-10">
                                <button type="submit" class="btn btn-primary col-sm-4">Submit</button>
                            </div>
                        </form>
                    </div>
            </div>
    </div>
</div>
</section>
@endsection
