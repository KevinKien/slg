@extends('app')

@section('htmlheader_title', 'Tùy chỉnh')

@section('js-current')
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $('#btn-generate-mp').click(function () {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('settings.generate-master-password') }}',
                    data: '_token={{ csrf_token() }}',
                    cache: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $('#btn-generate-mp').attr('disabled', 'disabled');
                    },
                    success: function (result) {
                        $('#btn-generate-mp').removeAttr('disabled');
                        $('#master-password').val(result.password);

                        if (result.expired_at !== '') {
                            $('#mp-expired-at').text('Hết hạn lúc: ' + result.expired_at);
                        }
                    }
                });
            });
        });
    </script>
@endsection

@section('css-current')
    <link rel="stylesheet" href="{{ asset('/plugins/iCheck/all.css') }}">
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header">
                    <i class="fa fa-gears"></i>

                    <h3 class="box-title">Tùy chỉnh</h3>
                </div>

                {!! Form::open([
                       'route' => 'settings.update',
                       'class' => 'form-horizontal',
                ]) !!}

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('request-log', 'Tắt/mở Request Log', ['class' => 'col-sm-7 control-label']) !!}
                        <div class="col-sm-5">
                            <input id="request-log" name="request-log" type="checkbox"
                                   class="flat-red" {{ $request_log }}>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('cyberpay', 'Tắt/mở Cổng thanh toán thẻ cào CyberPay', ['class' => 'col-sm-7 control-label']) !!}
                        <div class="col-sm-5">
                            <input id="cyberpay" name="cyberpay" type="checkbox"
                                   class="flat-red" {{ $cyberpay }}>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('nganluong', 'Tắt/mở Cổng thanh toán Ngân Lượng ATM', ['class' => 'col-sm-7 control-label']) !!}
                        <div class="col-sm-5">
                            <input id="nganluong" name="nganluong" type="checkbox"
                                   class="flat-red" {{ $nganluong }}>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('paygate', 'Cổng thanh toán thẻ cào', ['class' => 'col-sm-7 control-label']) !!}
                        <div class="col-sm-5">
                            <label>
                                <input type="radio" name="paygate" class="flat-red"
                                       value="PayDirect" {{ ($scratch_card_paygate == 'PayDirect') ? 'checked="checked"' : '' }}>
                                PayDirect
                            </label>
                            <label>
                                <input type="radio" name="paygate" class="flat-red"
                                       value="NganLuong" {{ ($scratch_card_paygate == 'NganLuong') ? 'checked="checked"' : '' }}>
                                Ngân Lượng
                            </label>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! Form::submit('Lưu', ['class' => 'btn btn-primary pull-right']) !!}
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header">
                    <i class="fa fa-gears"></i>

                    <h3 class="box-title">Master Password</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('master-password', 'Master Password', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('master-password', $master_password['password'], ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="col-sm-5">
                            <span id="mp-expired-at">{{ (empty($master_password['expired_at'])) ? '' : ('Hết hạn lúc: ' . date('d/m/Y H:i:s', $master_password['expired_at'])) }}</span>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button id="btn-generate-mp" class="btn btn-primary pull-right">Tạo mới</button>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header">
                    <i class="fa fa-gears"></i>

                    <h3 class="box-title">Other Utilities</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <button class="btn btn-sm btn-danger" type="button"
                                onclick="window.location = '{{ route('clear-redis-session') }}'">Xóa Cache Redis Laravel Session
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
@endsection