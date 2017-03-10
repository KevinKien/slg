@extends('payment.web.layout_payweb')

@section('htmlheader_title')
Nạp Coins - Bước 2
@endsection

@section('content')
<section class="ContsSubBox v01_layout float_right">
    <h3 class="big floating_left">Bước 2 / 3. Nhập thông tin thẻ.</h3>

    <div class="space10"></div>

    <div class="space10"></div>
    <table class="tableStyle02 pg_table" cellpadding="0" cellspacing="0" width="100%">
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
                    @if (count($errors) > 0)
                    <div class="form-row cutebox cw260">
                        <div class="alert alert-danger">
                            @if($errors->first('field'))
                            <aside class="system_message alert">{{$errors->first('field')}}</aside>
                            @endif
                        </div>
                    </div>
                    @endif
                    <form name="listprovider" action="{{ url('//pay.slg.vn/topupcash/telco')}}" method="POST">	
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
                                        <option value="VMS">Mobifone</option>
                                        <option value="VNP">Vinaphone</option>
                                        <option selected="selected" value="VTT">Viettel</option>
                                        <option value="VTC">VTC Vcoin</option>
                                        <option value="FPT">FPT Gate</option>
                                        <option value="MGC">Megacard</option>
                                    </select>
                                </div>
                            </div><!--//form-row-->
                            <div class="form-row cutebox cw260">
                                <div class="c-left"><label class="pad-left">Mã Thẻ:</label></div>
                                <div class="c-right">
                                    <input type="text" autofocus="autofocus" name="card_code" class="textbox-pop">
                                    @if($errors->first('card_code'))
                                    <aside class="system_message alert">{{$errors->first('card_code')}}</aside>
                                    @endif
                                </div>
                            </div><!--//form-row-->
                            <div id="cardSeri1" class="form-row cutebox cw260">
                                <div class="c-left"><label class="pad-left">Số seri:</label></div>
                                <div class="c-right">
                                    <input type="text" name="card_seri" class="textbox-pop">
                                    @if($errors->first('card_seri'))
                                    <aside class="system_message alert">{{$errors->first('card_seri')}}</aside>
                                    @endif
                                </div>

                            </div><!--//form-row-->


                            <div class="form-row cutebox cw260">
                                <div class="c-left"><label class="pad-left">Số điện thoại:</label></div>
                                <div class="c-right">
                                    <input type="text" name="order_mobile" class="textbox-pop">
                                    @if($errors->first('order_mobile'))
                                    <aside class="system_message alert">{{$errors->first('order_mobile')}}</aside>
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
