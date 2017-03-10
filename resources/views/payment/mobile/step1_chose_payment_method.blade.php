@extends('payment.mobile.layout_paymobile')

@section('htmlheader_title', 'Nạp Coins')

@section('content')
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
            <span class="user-coin"><a href="#">Tài khoản: {{App\Models\CashInfo::getCoins()}} Coin</a></span>
            <!--<button role="button" class="btn btn-default pull-right">THOÁT</button>-->
        </div>
    </div>
    <div class="id-path">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span> Trang chủ</a></li>
            <li><a href="#">Nạp Coin</a></li>
            <li class="active">Chọn hình thức nạp</li>
        </ol>
    </div>
    <div class="container">
        <?php $q = http_build_query($params) ?>
        <div class="col-xs-6 col-sm-6 card-panel">
            <input type="radio" name="charge" id="card" class="card-input">
            <label class="radio-inline" for="card" onclick="location.href = '//pay.slg.vn/mtopupcash/telco?<?php echo $q ?>';">
                Thẻ cào viễn thông<br>
                <img src="//pay.slg.vn/mtopup/images/card.gif" border="0"/>
            </label>
        </div>

        <div class="col-xs-6 col-sm-6 card-panel">
            <input type="radio" name="charge" id="bank" class="card-input">
            <label class="radio-inline" for="bank" onclick="location.href = '//pay.slg.vn/mtopupcash/bank?<?php echo $q ?>';">
                Nạp qua ATM, VISA, Bank<br>
                <img src="//pay.slg.vn/mtopup/images/bank.gif" border="0"/>
            </label>
        </div>

        <!--
        <div>
            <button role="button" class="btn btn-danger pull-right">NẠP COIN</button>
        </div>-->
    </div>
</div>

@endsection
