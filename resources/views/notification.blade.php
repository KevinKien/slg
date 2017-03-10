@extends('app')

@section('htmlheader_title', 'Gửi thông báo đến thiết bị')

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Gửi thông báo đến thiết bị</h3>
					</div><!-- /.box-header -->

                    {!! Form::open([
                       'route' => 'notification',
                       'class' => 'form-horizontal',
                       'method' => 'POST'
                    ]) !!}

					<div class="box-body">
                        <div class="form-group">
                            {!! Form::label('client_id', 'Game *', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                            <?php
                                $_client_ids = is_null(old('client_id')) ? [] : old('client_id');
                                $_client_types = is_null(old('client_type')) ? [] : old('client_type');
                            ?>
                            @foreach ($clients as $client_id => $client_name)
                                <div class="checkbox">
                                    <label>
                                        <input name="client_id[]" value="{{ $client_id }}" type="checkbox"
                                            @if (in_array($client_id, $_client_ids))
                                                checked="checked"
                                            @endif>
                                        {{ $client_name }}
                                    </label>
                                </div>
                            @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('client_type', 'Hệ điều hành *', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                            @foreach ($client_types as $type_id => $type_name)
                                <div class="checkbox">
                                    <label>
                                        <input name="client_type[]" value="{{ $type_id }}"  type="checkbox"
                                            @if (in_array($type_id, $_client_types))
                                                checked="checked"
                                            @endif>
                                        {{ $type_name }}
                                    </label>
                                </div>
                            @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('title', 'Tiêu đề *', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                                {!! Form::text('title', old('title'), ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('message', 'Nội dung *', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                                {!! Form::textarea('message', old('message'), ['class' => 'form-control']) !!}
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

        {{--@if(isset($users))--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Các thông báo gần đây</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="user-table" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--@foreach ($users as $user)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $user->id }}</td>--}}
                                        {{--<td>{{ $user->name }}</td>--}}
                                        {{--<td>{{ $user->email }}</td>--}}
                                        {{--<td><a href="{{ route('user.edit', $user->id) }}"><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</button></a></td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            {{--{!! $users->appends(['keyword' => Request::get('keyword')])->render() !!}--}}
                        </div>
                    </div>
                </div>
            </div>
        {{--@endif--}}
@endsection