@extends('app')

@section('htmlheader_title')
    Trang cập nhật một Oauthent client
@endsection
@section('contentheader_title','Cập nhật một Oauthent client')
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-editoauthent' accept-charset='UTF-8' method='post' action='/oauth_client_endpoints/edit?client_id=<?php print $_GET['client_id'];?>&id=<?php print $_GET['id'];?>&secret=<?php print $_GET['secret'];?>'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group @if ($errors->has('game')) has-error @endif">
                        <label> Hãy nhập tên game: </label> 
                        
                        @foreach($results as $row)
                            
                        @endforeach
                        <input id='game' class='form-control' name='game' required="Vui lòng điền vào trường nay!" value="{{$row->name}}{{old('game')}}">
                        @if ( $errors->has('game') )<p class="help-block" style="color: red;">{{ $errors->first('game') }}</p> @endif
                    </div>
       
                    <div class="form-group @if ($errors->has('redirect_uri')) has-error @endif">
                        <label>Nhập Url callback</label>
                        <input id='redirect_uri' class='form-control' type='url' name='redirect_uri' required="Vui lòng điền vào trường nay!" value="{{$row->redirect_uri}}{{old('redirect_uri')}}">
                        @if ( $errors->has('redirect_uri') )<p class="help-block" style="color: red;">{{ $errors->first('redirect_uri') }}</p> @endif
                    </div>

                    <div class="form-group @if ($errors->has('welcome-message')) has-error @endif">
                        {!! Form::label('welcome-message', 'Welcome Message') !!}

                        {!! Form::text('welcome-message', $welcome_message, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group @if ($errors->has('inreview_sdk_version')) has-error @endif">
                        <div class="col-sm-1">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<" name="inreview_operator" {{ ($inreview_operator == '<' || old('inreview_operator') == '<') ? 'checked' : '' }}>
                                    <
                                </label>
                                <label>
                                    <input type="radio" value=">=" name="inreview_operator" {{ ($inreview_operator == '>=' || old('inreview_operator')) == '>=' ? 'checked' : '' }}>
                                    >=
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-11">
                            <label>In-review SDK Version</label>
                            <input class='form-control' name='inreview_sdk_version' value="{{ $inreview_sdk_version }}">
                            @if ( $errors->has('inreview_sdk_version') )<p class="help-block"
                                                                           style="color: red;">{{ $errors->first('inreview_sdk_version') }}</p> @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                    <a href='/oauth_client_endpoints/delete?id={{$row->client_id}}'onclick='return confirmSubmit()'>Xóa</a>
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