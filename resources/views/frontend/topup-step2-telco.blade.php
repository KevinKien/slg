@extends('frontend.pay-layout')

@section('title', 'Thanh toán bằng thẻ viễn thông')

@section('additional-script')
    <script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
    <script>
        $('#btn-submit').on('click', function () {
            $('input[type=text]').each(function(){
                $(this).val($.trim($(this).val()));
            })

            $(this).attr('disabled', 'disabled');
            $('#frm').submit();
        });
    </script>
@endsection

@section('pay-content')
    <div class="panel panel-default">
        <div class="panel-heading custom-panel-heading">Bước 2: Nhập thông tin thẻ</div>
        <div class="panel-body">

            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                SLG xin thông báo tỉ lệ quy đổi Coin khi thanh toán qua <strong>thẻ cào viễn thông</strong> từ 12h trưa ngày 05/05/2016 thay đổi từ 1 Coin bằng 100 VND sang tỉ lệ mới là: <strong>Số Coin nhận được = Mệnh giá thẻ * 0.95% (làm tròn xuống)</strong>
                <br>Ví dụ: Thẻ 10k VND tương đương 95 Coin, Thẻ 100k VND tương đương 950 Coin.
                <br> Lưu ý: tỉ lệ quy đổi khi thanh toán qua thẻ ATM, thẻ tín dụng vẫn giữ nguyên: 1 Coin bằng 100 VND.
            </div>

            @include('frontend.partials.message')

            {!! Form::open([
					'route' => 'topupcash.post.telco',
					'class' => 'form-horizontal',
					'id'    => 'frm'
				]) !!}

            <div class="form-group @if($errors->first('card_type')) has-error @endif">
                <label for="card_type" class="col-sm-offset-1 col-sm-2 control-label">Loại thẻ <span
                            class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <select class="form-control" name="card_type" id="card_type">
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
            </div>

            <div class="form-group @if($errors->first('card_code')) has-error @endif">
                <label for="card_code" class="col-sm-offset-1 col-sm-2 control-label">Mã thẻ <span
                            class="text-danger">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('card_code', old('card_code'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group @if($errors->first('card_seri')) has-error @endif">
                <label for="card_seri" class="col-sm-offset-1 col-sm-2 control-label">Số Serial <span
                            class="text-danger">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('card_seri', old('card_seri'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group @if($errors->first('order_mobile')) has-error @endif">
                <label for="order_mobile" class="col-sm-offset-1 col-sm-2 control-label">Số điện thoại <span
                            class="text-danger">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('order_mobile', old('order_mobile'), ['class' => 'form-control']) !!}
                </div>
            </div>

            {{--<div class="form-group">--}}
                {{--<label class="col-sm-offset-1 col-sm-2 control-label">Xác thực <span--}}
                            {{--class="text-danger">*</span></label>--}}
                {{--<div class="col-sm-6">--}}
                    {{--<div class="g-recaptcha" data-sitekey="6LfDGBMTAAAAAMaKjZqtAMvD7yjHIrj3h16kShZO"></div>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="button" class="btn btn-default"
                            onclick="location.href='{{ route('topupcash.index') }}'">Quay lại
                    </button>
                    <button id="btn-submit" type="button" class="btn btn-danger">Nạp thẻ</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection