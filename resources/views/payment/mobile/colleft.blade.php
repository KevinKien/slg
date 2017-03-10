<section class="col_left float_left">
    <div class="payment_info">
        <div class="payinfo_header">Thông tin tài khoản</div>
        <div class="space10"></div>
        <div class="payinfo_content">
            <ul>
                <li><a><span>Mã tài khoản:<strong>{{Auth::user()->id}}</strong></span></a> </li>
                <li><a><span>Tài khoản chính:<strong>{{App\Models\CashInfo::getCoins()}}</strong></span></a> </li>
                <li><a><span>Tài khoản khuyến mại:<strong>{{App\Models\CashInfo::getPoint()}}</strong></span></a> </li>
            </ul>
        </div>
    </div>
    <!--
    <div class="payment_task">
        <div class="paytask_header">Điều hướng</div>
        <div class="paytask_content">
            <ul>
                <li> <a href=""><span>Nạp thêm Coin</span></a> </li>
                <li> <a href=""><span>Nạp Coin vào Game</span></a> </li>
                <li> <a href=""><span>Các giao dịch</span></a> </li>
            </ul>
        </div>
    </div>-->
</section>