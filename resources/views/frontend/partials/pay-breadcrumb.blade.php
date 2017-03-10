<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li class="text-big">Thanh toán</li>
            <li>{!! HTML::link(route('topupcash.index', [], false), 'Nạp Coin vào ví',  (strpos(Route::currentRouteName(), 'topupcash') !== false) ? array('class' => 'on') : null) !!}</li>
            <li>{!! HTML::link(route('topcoin'), 'Chuyển Coin vào Game',  (strpos(Route::currentRouteName(), 'topcoin') !== false) ? array('class' => 'on') : null) !!}</li>
            <li>{!! HTML::link(route('transaction-history'), 'Lịch sử giao dịch',  (strpos(Route::currentRouteName(), '-history') !== false) ? array('class' => 'on') : null) !!}</li>
        </ol>
    </div>
</div>