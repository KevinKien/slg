@extends('app')

@section('htmlheader_title', 'Ghép người dùng FPAY')

@section('css-current')
@endsection

@section('js-current')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Ghép người dùng FPAY</h3>
                </div><!-- /.box-header -->

                {!! Form::open([
                   'route' => 'user.fix.post',
                   'class' => 'form-horizontal',
                   'method' => 'POST'
                ]) !!}

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('name', 'Tên người dùng *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', 'Copy mật khẩu từ FPAY sang', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <input type="checkbox"
                                   name="replace_password" {{ old('replace_password') == 1 ? 'checked' : '' }}>
                        </div>
                    </div>


                </div><!-- /.box-body -->


                <div class="box-footer">
                    <a href="javascript:history.back();" class="btn btn-default">Back</a>
                    {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
                </div><!-- /.box-footer -->

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection