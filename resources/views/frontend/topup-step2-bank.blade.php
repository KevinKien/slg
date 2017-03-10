@extends('frontend.pay-layout')

@section('title', 'Thanh toán bằng thẻ ATM, Visa, Master, JCB')

@section('additional-script')
    <script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
    <script>
        $('#btn-submit').on('click', function () {
            $(this).attr('disabled', 'disabled');
            $('#frm').submit();
        });
    </script>
@endsection

@section('pay-content')
    <div class="panel panel-default">
        <div class="panel-heading custom-panel-heading">Bước 2: Lựa chọn loại thẻ và mệnh giá</div>
        <div class="panel-body">
            @include('frontend.partials.message')

            {!! Form::open([
            'route' => 'topupcash.post.bank',
            'class' => 'form-horizontal',
            'id' => 'frm',
            ]) !!}

            <div class="form-group">
                <label for="card_type" class="col-sm-offset-1 col-sm-2 control-label">Loại thẻ <span
                            class="text-danger">*</span></label>

                <div class="col-sm-6">
                    <select class="form-control" name="card_type" id="card_type">
                            <option selected="selected" value="visa">Visa, Master, JCB, Amex</option>
                        <option value="atm">Thẻ ATM</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="money" class="col-sm-offset-1 col-sm-2 control-label">Số tiền <span
                            class="text-danger">*</span></label>

                <div class="col-sm-6">
                    <select class="form-control" name="money" id="money">
                        @foreach ($moneys as $money)
                            <option value="{{ $money }}"{{ $money == old('money') ? ' selected=selected' : '' }}>{{ number_format($money, 0, ',', '.') }}
                                VNĐ
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="game" class="col-sm-offset-1 col-sm-2 control-label">Game <span
                            class="text-danger">*</span></label>

                <div class="col-sm-6">
                    <select class="form-control" name="game" id="game">
                        <option value="TQTK">Tam Quốc Truyền Kỳ</option>
                        <option value="LV">Linh Vương</option>
                        <option value="OP">One Piece Online</option>
                        <option value="TLDH">Tiểu Long Du Hý</option>
                    </select>
                </div>
            </div>

            {{--<div class="form-group">--}}
                {{--<label for="order_mobile" class="col-sm-offset-1 col-sm-2 control-label">Xác thực <span--}}
                            {{--class="text-danger">*</span></label>--}}
                {{--<div class="col-sm-6">--}}
                    {{--<div class="g-recaptcha" data-sitekey="6LfDGBMTAAAAAMaKjZqtAMvD7yjHIrj3h16kShZO"></div>--}}
                {{--</div>--}}
            {{--</div>--}}


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <p>Tỉ lệ quy đổi: 1 coin = 100 VNĐ.</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="button" class="btn btn-default"
                            onclick="location.href ='{{ route('topupcash.index') }}'">Quay lại
                    </button>
                    <button type="submit" id="btn-submit" class="btn btn-danger">Thanh toán</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection