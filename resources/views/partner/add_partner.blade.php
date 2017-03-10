@extends('app')

@section('htmlheader_title', 'Trang thêm mới một Đối tác')
@section('contentheader_title','Thêm một đối tác')
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/partner'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group @if ($errors->has('partner-name')) has-error @endif">
                        <label> Hãy nhập tên Đối tác: </label>
                        <input id='partner-name' class='form-control' type='text'name='partner-name' value="{{old('partner-name')}}" >
                        @if ( $errors->has('partner-name') )<p class="help-block" style="color: red;">{{ $errors->first('partner-name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('payment-url-callback')) has-error @endif">
                        <label>Hãy nhập payment_url_callback</label>
                        <input id='payment-url-callback' class='form-control' type='text'name='payment-url-callback' value="{{old('payment-url-callback')}}">
                        @if ( $errors->has('payment-url-callback') )<p class="help-block" style="color: red;">{{ $errors->first('payment-url-callback') }}</p> @endif
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Thêm</button>
                    <a href='javascript:goback()'>Cancel</a>
                </div>
            </form>
            
        </div>
        <script>
        function goback() {
        history.back(-1)
        }</script>
    
@endsection