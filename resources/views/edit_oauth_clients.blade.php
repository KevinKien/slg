@extends('app')

@section('htmlheader_title')
    Trang Cập nhật một Oauthent client
@endsection
@section('contentheader_title','Cập nhật Oauthent client')
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
            <form id='form-addoauthent' accept-charset='UTF-8' method='post' action='/oauth_clients/edit?id=<?php print $_GET['id']?>'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group">
                        <label> Hãy nhập game: </label>
                        @foreach($results as $row)
                            
                        @endforeach
                        <input id='game' class='form-control' name='game' required="Vui lòng điền vào trường nay!" value="{{$row->name}}">
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                    <a href='/oauth_clients/delete?id=<?php print $_GET['id']?>'onclick='return confirmSubmit()'>Xóa</a>
                </div>
            </form>
            
        </div>
            <script LANGUAGE="JavaScript">
                function confirmSubmit()
                {
                var agree=confirm("Bạn có muốn xóa mục này không? Nếu xóa thì dữ liệu xẽ bị mất");
                if (agree)
                    return true ;
                else
                    return false ;
                }
        </script>
@endsection