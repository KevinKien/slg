@extends('frontend.pay-layout')

@section('title', 'Thanh toán qua Ngân Lượng')

@section('pay-content')
<div class="panel panel-default">
    <div class="panel-heading custom-panel-heading">Bước 2: Lựa chọn số tiền muốn nạp</div>
    <div class="panel-body">
        @include('frontend.partials.message')

        {!! Form::open([
        'route' => 'topupcash.post.nl',
        'class' => 'form-horizontal'
        ]) !!}

        <div class="form-group">
            <label for="money" class="col-sm-offset-1 col-sm-2 control-label">Số tiền <span
                    class="text-danger">*</span></label>

            <div class="col-sm-6">
                <select class="form-control" name="money" id="money">
                    @foreach ($moneys as $money)
                        <option value="{{ $money }}"{{ $money == old('money') ? ' selected=selected' : '' }}>{{ number_format($money, 0, ',', '.') }} VNĐ</option>
                    @endforeach
                </select>
            </div>
        </div>

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
                <button type="submit" class="btn btn-danger">Thanh toán</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection