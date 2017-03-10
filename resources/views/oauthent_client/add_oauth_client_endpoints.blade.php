@extends('app')

@section('htmlheader_title')
    Trang thêm mới một Oauthent client
@endsection
@section('contentheader_title','Thêm Oauthent client')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>

        <form id='form-addoauthent' accept-charset='UTF-8' method='post' action='/oauth_client_endpoints'>
            <div class="box-body">
                <input name='_token' type='hidden' value='csrf_token()'>

                <div class="form-group @if ($errors->has('game')) has-error @endif">
                    <label> Hãy nhập tên game: </label>
                    <input id='game' class='form-control' name='game' value="{{old('game')}}">
                    @if ( $errors->has('game') )<p class="help-block"
                                                   style="color: red;">{{ $errors->first('game') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('redirect_uri')) has-error @endif">
                    <label>Nhập Url callback</label>
                    <input id='redirect_uri' class='form-control' name='redirect_uri' value="{{old('redirect_uri')}}">
                    @if ( $errors->has('redirect_uri') )<p class="help-block"
                                                           style="color: red;">{{ $errors->first('redirect_uri') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('welcome-message')) has-error @endif">
                    {!! Form::label('welcome-message', 'Welcome Message') !!}

                    {!! Form::text('welcome-message', old('welcome-message'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group @if ($errors->has('inreview_sdk_version')) has-error @endif">
                    <div class="col-sm-1">
                        <div class="radio">
                            <label>
                                <input type="radio" value="<"
                                       name="inreview_operator" {{ old('inreview_operator') == '<' ? 'checked' : '' }}>
                                <
                            </label>
                            <label>
                                <input type="radio" value=">="
                                       name="inreview_operator" {{ old('inreview_operator') == '>=' ? 'checked' : '' }}>
                                >=
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-11">
                        <label>In-review SDK Version</label>
                        <input class='form-control' name='inreview_sdk_version' value="{{old('inreview_sdk_version')}}">
                        @if ( $errors->has('inreview_sdk_version') )<p class="help-block"
                                                                       style="color: red;">{{ $errors->first('inreview_sdk_version') }}</p> @endif
                    </div>
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