@extends('payment.web.layout_payweb')

@section('htmlheader_title')
Nạp Coins - Bước 2
@endsection

@section('content')
<section class="ContsSubBox v01_layout float_right">
    <h3 class="big floating_left">Bước 2 / 3. Lựa chọn loại thẻ và mệnh giá.</h3>

    <div class="space15"></div>
    <table class="tableStyle02 pg_table" cellpadding="0" cellspacing="0" width="100%" style="margin-top:15px;">
        <colgroup>
            <col width="188">
            <col width="*">
        </colgroup>
        <tbody>
            <tr>
                <th class="borderStyle v01_th_sty hide_row" style="width: 65%" >&nbsp;</th>
                <td class="borderStyle hide_row">
                    &nbsp;                         
                </td>
            </tr>
            <tr>
                <td class="borderStyle" style="border:none" colspan="2">
                    <form name="listprovider" action="{{ url('//pay.slg.vn/topupcash/bank')}}" method="POST">	
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">    
                        <div class="form-buy cutebox cw364">
                            <div class="form-row error cutebox cw260">
                                <div class="c-left"></div>
                                <div class="c-right">
                                    <span class="tc-red"></span>
                                </div>
                            </div><!--//form-row-->
                            <div class="form-row cutebox cw260">
                                <div class="c-left"><label class="pad-left">Loại thẻ:</label></div>
                                <div class="c-right">
                                    <select class="selectbox-pop textbox-pop" name="card_type" id="card_type">
                                        <option selected="selected" value="visa">Visa, Master, JCB</option>
                                        <option value="atm">Thẻ ATM</option>   
                                    </select>
                                </div>
                            </div><!--//form-row-->
                            <div class="form-row cutebox cw260">
                                <div class="c-left"><label class="pad-left">Số tiền nạp:</label></div>
                                <div class="c-right">
                                    <select class="selectbox-pop textbox-pop" name="money" id="money">
                                        <option selected="selected" value="100000">100.000 VNĐ</option>
                                        <option value="200000">200.000 VNĐ</option>
                                        <option value="500000">500.000 VNĐ</option>
                                        <option value="1000000">1.000.000 VNĐ</option>
                                        <option value="10000">10.000 VNĐ</option>
                                    </select>
                                    @if($errors->first('card_code'))
                                    <aside class="system_message alert">{{$errors->first('card_code')}}</aside>
                                    @endif
                                </div>
                            </div><!--//form-row-->

                        </div><!--//cw363-->


                        <div class="group-btn">

                            <label class="btn-gray"><input type="button" value="Quay lại" onclick="location.href = '{{ url('//pay.slg.vn/topupcash')}}'" name="back" id="back"></label>
                            <label class="btn-green"><input type="button" value="Nạp thẻ" onclick="javascript:document.listprovider.submit();" id="submit_btn"></label>

                        </div><!--//label-->
                    </form>
                </td>
            </tr>

        </tbody></table>
    <div class="space15"></div>

</section>
@endsection
