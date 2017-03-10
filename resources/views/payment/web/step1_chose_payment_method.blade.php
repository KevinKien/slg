@extends('payment.web.layout_payweb')

@section('htmlheader_title')
Nạp Coins
@endsection

@section('content')
<section class="ContsSubBox v01_layout float_right">
    <!--
    <div class="py_top_div sub_display">
        <article class="user_wcoin_bx">
            <h3>Tài khoản:</h3>
            <strong class="icon_wcoin_mark">50</strong>
        </article>
    </div>-->
    <h3 class="big floating_left">Bước 1 / 3. Lựa chọn hình thức thanh toán.</h3>

    <form action="/BuyWCoin/PaymentMethod" method="post">  
        <div class="space10"></div>
        <div class="select_available">
            <aside class="system_message alert">
                <div class="validation-summary-valid" data-valmsg-summary="true"><ul><li style="display:none"></li>
                    </ul></div>

            </aside>
        </div>
        <div class="space10"></div>
        <table class="tableStyle02 pg_table" cellpadding="0" cellspacing="0" width="100%">
            <colgroup>
                <col width="188">
                <col width="*">
            </colgroup>
            <tbody><tr>
                    <th class="borderStyle v01_th_sty">Thẻ viễn thông</th>
                    <td class="borderStyle">
                        <ul>
                            <li><a href="/topupcash/telco"><img src="/portal/images/card.png"></a></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th class="v01_th_sty">Thẻ ATM hoặc thẻ VISA, MASTER, JCB</th>
                    <td class="borderStyle" style="border-top:none;">
                        <ul>
                            <li><a href="/topupcash/bank"><img src="/portal/images/bank.jpg"></a></li>
                        </ul>                            
                    </td>
                </tr>

            </tbody></table>
        <div class="space15"></div>
        <input id="countryNo" name="countryNo" value="" type="hidden">
    </form>
</section>

@endsection
