@extends('frontend.pay-layout')

@section('title', 'Lịch sử giao dịch')

@section('pay-content')
    <div class="panel panel-default">
        <div class="panel-heading custom-panel-heading">Lịch sử giao dịch</div>
        <div class="panel-body">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="{{ Route::is('topup-history') ? 'active' : '' }}"><a
                            href="{{ route('topup-history') }}">Lịch sử nạp SCoin</a></li>
                <li role="presentation" class="{{ Route::is('transfer-coin-history') ? 'active' : '' }}"><a
                            href="{{ route('transfer-coin-history') }}">Lịch sử chuyển SCoin</a></li>
            </ul>
            @yield('transaction-history')
        </div>
        @yield('transaction-history-pagination')
    </div>
@endsection