@extends('app')

@section('htmlheader_title')
    Trang thêm mới một Oauthent client
@endsection
@section('contentheader_title','Thêm Oauthent client')
@endsection
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            @if ( $errors->any() )
            <ul>
			@foreach ($errors->all() as $error)
				<li style="color: red;">{{ $error }}</li>
			@endforeach
            </ul>	
            @endif
            <form id='form-addoauthent' accept-charset='UTF-8' method='post' action='/oauth_clients'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group">
                        <label> Hãy nhập game: </label>
                         <input id='game' class='form-control' name='game' required="Vui lòng điền vào trường nay!">
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