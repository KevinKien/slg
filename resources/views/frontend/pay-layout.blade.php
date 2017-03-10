@extends('frontend.master-layout')

@section('additional-css')
<link rel="stylesheet" href="{{ asset('/frontend/css/pay.css', true) }}">
@endsection

@section('content')
    <div class="container">
        @include('frontend.partials.pay-breadcrumb')
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item custom-panel-heading">
                        Thông tin tài khoản
                    </li>
                    <li class="list-group-item">
                        <span class="badge">{{ Auth::id() }}</span>
                        Mã tài khoản
                    </li>
                    <?php $cash = App\Models\CashInfo::getCashInfo(Auth::id()) ?>
                    <li class="list-group-item">
                        <span class="badge">{{ $cash['coin'] }}</span>
                        Tài khoản chính
                    </li>
                    {{--<li class="list-group-item">--}}
                        {{--<span class="badge">{{ $cash['point'] }}</span>--}}
                        {{--Tài khoản khuyến mại--}}
                    {{--</li>--}}
                </ul>
            </div>
            <div class="col-md-9">
            @yield('pay-content')
            </div>
        </div>
    </div>
@endsection