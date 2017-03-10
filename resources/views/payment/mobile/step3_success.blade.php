@extends('payment.mobile.layout_paymobile')

@section('htmlheader_title')
Nạp Coins - Bước 3
@endsection

@section('content')
<!--
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
            <span class="user-name">{{Auth::user()->name}}</span>
            <!--<span class="user-coin"><a href="#">Tài khoản: {{App\Models\CashInfo::getCoins()}} Coin</a></span>
            <button role="button" class="btn btn-default pull-right">THOÁT</button>-->
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-xs-4">
            <img src="//pay.slg.vn/mtopup/images/success.gif" border="0" height="100" class="pull-right"/>
        </div>
        <div class="col-xs-8 notify-success">
            @if(isset($message) && !empty($message))
            {{$message}}
            @else
            Bạn đã nạp tiền thành công !
            @endif
            <br>
            <!--Bạn nạp <strong>10.000 VNĐ</strong> được <strong>10 Coin</strong><br>>-->
            Số Coin hiện có: <strong>{{App\Models\CashInfo::getCoins()}} Coin</strong>
        </div>
        @if($access_token)
        <div class="col-md-12">
            <button onclick="location.href = '{{ url('//pay.slg.vn/mtopupcash?access_token='.$access_token)}}'" role="button" class="btn btn-danger pull-right">NẠP THÊM</button>
        </div>
        @endif
    </div>
</div>

@endsection
