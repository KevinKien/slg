@extends('frontend.master-layout')

@section('content')
<div class="container khungdangnhap">
        <div class="row">
            <div class="col-xs-12 wraping">
                <h4 class="">ĐĂNG NHẬP</h4>
                <form>
                    <div class="form-group">
                      <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Tên tài khoản . . .">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mật khẩu . . .">
                    </div>
                    <div class="row">
                        <a href="#" class="quenmatkhau">Quên mật khẩu ?</a>               
                        <button type="submit" class="btn btn-default">Đăng Nhập</button>
                    </div>
                    <div class="row">
                        <a href="#"><img src="images/fblogin_11.png"></a> 
                    </div>
                    <a href="#" class="dangky">Chưa có tài khoản ? Đăng ký ngay</a>
                </form>
            </div>
        </div>
      </div> 

      <div class="games-container">
      </div>
@endsection