@extends('payment.web.layout_payweb')

@section('htmlheader_title')
Nạp Coins - Bước 3
@endsection

@section('content')
<section class="ContsSubBox v01_layout float_right">
    <h3 class="big floating_left">Bước 3 / 3. Giao dịch thành công.</h3>

    <div class="space10"></div>

    <div class="space10"></div>
    <table class="tableStyle02 pg_table" cellpadding="0" cellspacing="0" width="100%">
        <colgroup>
            <col width="188">
            <col width="*">
        </colgroup>
        <tbody>
            <tr>
                <th class="borderStyle v01_th_sty hide_row" colspan="2" >&nbsp;</th>

            </tr>
            <tr>
                <td class="borderStyle" style="border:none" colspan="2">
                    <div class="form-buy cutebox cw364">
                        <p style="text-align:center; padding-top:40px;">
                            <img src="//pay.slg.vn/portal/images/success_icon.png" width="250px"/>
                        </p>
                        <div class="group-btn">
                            <label class="btn-green" style="float:right;"><input type="button" value="Nạp thêm" onclick="location.href = '{{ url('//pay.slg.vn/topupcash')}}'" id="submit_btn"></label>
                        </div><!--//label-->
                    </div><!--//cw363-->
                </td>
            </tr>

        </tbody></table>
    <div class="space15"></div>

</section>

@endsection
