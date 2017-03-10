@extends('app')
@section('contentheader_title', 'Thông tin profile')
@section('css-current')
    <link href="//id.slg.vn/css/profilestyle.css" rel="stylesheet"/>
@endsection
@section('main-content')
<div class="box">
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
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">THÔNG TIN NGƯỜI DÙNG</h3>
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
                                <td><a class="btn btn-primary" href="//id.slg.vn/profile/changepersonalinfo">Đổi thông tin</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection