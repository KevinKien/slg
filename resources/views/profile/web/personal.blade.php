@extends('layout.newprofileweb.layout')
@section('css-current')
    <!-- Bootstrap 3.3.4 -->
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
@endsection
@section('htmlheader_title')
Thông tin người dùng
@endsection
@section('content')
<section id="ContentWrap">
<div class="box ">
    <div class="row">
        <div class="col-md-4" >
            @include('profile.web.profilebar')
        </div>
        <div class="col-md-8" >
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Thông tin người dùng</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table profiletable">
                        <tbody>
                            <tr >
                                <td class="labels">Họ tên </td>
                                <td><b>{{ isset(Auth::user()->fullname)?Auth::user()->fullname:'(Chưa có thông tin)' }}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="labels">Giới tính</td>
                                <?php 
                                    if(isset(Auth::user()->sex)){
                                        $show_sex = Auth::user()->sex ==1?'Nam':'Nữ';
                                    }else{
                                        $show_sex = '(Chưa có thông tin)';
                                    }
                                ?>
                                <td><b>{{ $show_sex }}</b></td>
                                <td></td>
                            </tr>
                            <tr >
                                <td class="labels">Số CMT</td>
                                <td><b>{{ strlen( Auth::user()->identify)>6?substr(Auth::user()->identify,0,3).'*******'.substr(Auth::user()->identify,-3,3):'(Chưa có thông tin)'}}</b></td>
                                <td></td>
                            </tr>
                            <tr >
                                <td class="labels">Ngày sinh</td>
                                <?php
                                    if(!empty(Auth::user()->birthday)){
                                        $show_bir = date('d-m-Y',strtotime(Auth::user()->birthday ));
                                    }else{
                                        $show_bir = '(Chưa có thông tin)';
                                    }
                                    
                                ?>
                                <td><b>{{ $show_bir  }}</b></td>
                                <td></td>
                            </tr>
                            <tr >
                                <td class="labels">Địa chỉ</td>
                                    <?php
                                        if(isset(Auth::user()->address)){
                                            $show_addr = Auth::user()->address ;
                                        }else{
                                            $show_addr = '(Chưa có thông tin)';
                                        }

                                    ?>
                                <td><b>{{ $show_addr }}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="labels">Email đăng nhập</td>
                                <td><b>{{ !empty( Auth::user()->email)?substr(Auth::user()->email,0,3).'*******'.substr(Auth::user()->email,-3,3):'(Chưa có thông tin)' }}</b></td>
                                <td></td>
                            </tr>
                            <tr >
                                <td class="labels">SĐT đăng nhập</td>
                                <td><b>{{ strlen( Auth::user()->phone)>6?substr(Auth::user()->phone,0,3).'*******'.substr(Auth::user()->phone,-3,3):'(Chưa có thông tin)'}}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><a class="btn btn-primary" href="/profile/changepersonalinfo">Đổi thông tin</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
</section>
@endsection