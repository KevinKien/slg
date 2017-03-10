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
            <li class="active">Nhập thông tin thẻ</li>
        </ol>
    </div>
    <form name="listprovider" action="{{ url('//pay.slg.vn/mtopupcash/bank?access_token='.$access_token)}}"
          method="POST">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="container-fluid">
            @include('payment.mobile.message')

            <div class="form-group">
                <span class="form-left">Loại thẻ: </span>
                <select class="form-control form-right" name="card_type" id="card_type">
                    <option selected="selected" value="visa">Visa, Master, JCB</option>
                    <option value="atm">Thẻ ATM</option>
                </select>
            </div>
            <div class="form-group">
                <span class="form-left">Số tiền: </span>
                <select class="form-control form-right" name="money" id="money">
                    @foreach ($moneys as $money)
                    <option value="{{ $money }}"{{ $money == old('money') ? ' selected=selected' : '' }}>{{ number_format($money, 0, ',', '.') }}
                            VNĐ
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <span class="form-left">Game: </span>
            <select class="form-control form-right" name="game" id="game">
                <option value="TLDH">Tiểu Long Du Hý</option>
                <option value="TT">Thần Tiên Đạo</option>

            </select>
        </div>
        <div>
            <button onclick="location.href = '{{ url('//pay.slg.vn/mtopupcash?access_token='.$access_token)}}'"
                    type="button" class="btn btn-default">QUAY LẠI
            </button>
            <button onclick="javascript:document.listprovider.submit();" role="button"
                    class="btn btn-danger pull-right">NẠP COIN
            </button>
        </div>
    </div>
    <input type="hidden" name="access_token" value="{{$access_token}}"/>
</form>
</div>

@endsection
