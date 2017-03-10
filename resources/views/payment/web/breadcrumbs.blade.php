<article class="subMenu">
    <h1>Thanh toán</h1>
    <menu>
        <li>{!! HTML::secureLink('topupcash', 'Nạp thêm Coin',  (strpos(Route::currentRouteName(), 'topupcash') !== false) ? array('class' => 'on') : null) !!}</li>
        <li>{!! HTML::secureLink('topcoin', 'Nạp Coin vào Game', (strpos(Route::currentRouteName(), 'topcoin') !== false) ? array('class' => 'on') : null) !!}</li>
        <li>{!! HTML::secureLink('history', 'Lịch sử giao dịch',  Route::is('history') ? array('class' => 'on') : null) !!}</li>
    </menu>
</article>