@extends('payment.mobile.layout_paymobile')

@section('htmlheader_title')
Nạp Coins - Bước 3
@endsection

@section('content')<!--
<div class="row top-bar">
    <button class="toggle-menu menu-left push-body"><span class="glyphicon glyphicon-menu-hamburger"></span>
    </button>
    <span><strong>ID</strong><thin>SLG</thin></span>

</div>-->
<div class="row">
    <div class="user-container">
        <div class="user-dp">
            <a href="#">
            @if(!empty(Auth::user()->avatar))
                <img src="{{Auth::user()->avatar}}" width="40" height="40" border="0"/>
                @else
                    <img src="//pay.slg.vn/mtopup/images/default-avatar.png" border="0"/>
                @endif
            </a>
            <span class="user-name">@if(Auth::check()){{ Auth::user()->name }} @else Guest @endif</span>
            <span class="user-coin"><a href="#">Tài khoản: @if(Auth::check()) {{App\Models\CashInfo::getCoins()}}  @else Unknow @endif Coin</a></span>
            <!--<button role="button" class="btn btn-default pull-right">THOÁT</button>-->
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-xs-4">
            <img src="//pay.slg.vn/portal/images/fail_icon.png" border="0" height="100" class="pull-right"/>
        </div>
        <div class="col-xs-8 notify-success">
            {{$message}}<br>
            <!--Bạn nạp <strong>10.000 VNĐ</strong> được <strong>10 Coin</strong><br>-->
        </div>
        <div class="col-md-12">
            @if($access_token)
            <button onclick="location.href = '{{ url('//pay.slg.vn/mtopupcash?access_token='.$access_token)}}'" role="button" class="btn btn-danger pull-right">NẠP THÊM</button>
            @endif
        </div>
    </div>
</div>
@endsection
