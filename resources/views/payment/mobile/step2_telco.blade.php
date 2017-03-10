@extends('payment.mobile.layout_paymobile')

@section('htmlheader_title', 'Nạp Coins')

@section('content')
    <div class="row">
        <div class="user-container">
            <div class="user-dp">
                <a href="#">@if(!empty(Auth::user()->avatar))
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

        <form id="listprovider" name="listprovider" action="{{ url('//pay.slg.vn/mtopupcash/telco')}}" method="POST">
            <div class="container-fluid">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    SLG xin thông báo tỉ lệ quy đổi Coin khi thanh toán qua <strong>thẻ cào viễn thông</strong> từ 12h trưa ngày 05/05/2016 thay đổi từ 1 Coin bằng 100 VND sang tỉ lệ mới là: <strong>Số Coin nhận được = Mệnh giá thẻ * 0.95% (làm tròn xuống)</strong>
                    <br>Ví dụ: Thẻ 10k VND tương đương 95 Coin, Thẻ 100k VND tương đương 950 Coin.
                    <br> Lưu ý: tỉ lệ quy đổi khi thanh toán qua thẻ ATM, thẻ tín dụng vẫn giữ nguyên: 1 Coin bằng 100 VND.
                </div>

                @include('payment.mobile.message')

                <div class="form-group">
                    <span class="form-left">Loại thẻ: </span>
                    <select class="form-control form-right" name="card_type" id="card_type">
                        <option value="MOBI" {{ old('card_type') == 'MOBI' ? 'selected' : '' }}>Mobifone</option>
                        <option value="VINA" {{ old('card_type') == 'VINA' ? 'selected' : '' }}>Vinaphone</option>
                        <option value="VT" {{ old('card_type') == 'VT' ? 'selected' : '' }}>Viettel</option>
                        {{--@if($scratch_card_paygate == 'PayDirect')--}}
                            <option value="GATE" {{ old('card_type') == 'GATE' ? 'selected' : '' }}>GATE</option>
                        {{--@endif--}}
                        @if($cyberpay == 1)
                            <option value="CYBERPAY" {{ old('card_type') == 'CYBERPAY' ? 'selected' : '' }}>CyberPay
                            </option>
                        @endif
                    </select>
                </div>
                <div class="form-group @if($errors->first('card_code')) has-error @endif">
                    <span class="form-left">Mã thẻ: </span>
                    <input type="text" class="form-control form-right" name="card_code" id="card_code" value="{{ old('card_code') }}">
                </div>
                <div class="form-group @if($errors->first('card_seri')) has-error @endif">
                    <span class="form-left">Số Serial: </span>
                    <input type="text" class="form-control form-right" name="card_seri" id="card_seri" value="{{ old('card_seri') }}">
                </div>
                <div style="display:none" class="form-group @if($errors->first('order_mobile')) has-error @endif">
                    <span class="form-left">Số điện thoại: </span>
                    <input type="hidden" class="form-control form-right" name="order_mobile" value="9999999999">
                </div>
                <div>
                    <button onclick="location.href = '{{ url('//pay.slg.vn/mtopupcash?access_token='.$access_token)}}'"
                            role="button" class="btn btn-default">QUAY LẠI
                    </button>

                    <button id="submitform" role="button" class="btn btn-danger pull-right">NẠP COIN</button>
                </div>
            </div>
            <input type="hidden" name="access_token" value="{{$access_token}}"/>
        </form>
    </div>
    <script type="text/javascript">
        $(document).bind('pageinit', function () {
            $('input,select').keypress(function (event) {
                return event.keyCode != 13;
            });
        })

        $('#listprovider').submit(function () {
            // Get the Login Name value and trim it
            var card_code = $.trim($('#card_code').val());
            var card_seri = $.trim($('#card_seri').val());

            // Check if empty of not
            if (card_code === '') {
                $("#card_code").focus();
                return false;
            }
            // Check if empty of not
            if (card_seri === '') {
                $("#card_seri").focus();
                return false;
            }

            $('input[type=text]').each(function(){
                $(this).val($.trim($(this).val()));
            })

            $('#submitform').attr('disabled', 'disabled');
        });
    </script>
@endsection
