@extends('app')

@section('htmlheader_title', 'Log chuyển Coin')

@section('css-current')
    {!! HTML::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
@endsection

@section('js-current')
    {!! HTML::script('plugins/moment.min.js') !!}
    {!! HTML::script('plugins/daterangepicker/daterangepicker.js') !!}
    <script>
        $(function () {
            $('#daterange').daterangepicker(
                    {
                        ranges: {
                            'Hôm nay': [moment(), moment()],
                            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            '7 Ngày trước': [moment().subtract(6, 'days'), moment()],
                            '30 Ngày trước': [moment().subtract(29, 'days'), moment()],
                            'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(7, 'days'),
                        endDate: moment()
                    }
            );
        });
    </script>
@endsection


@section('main-content')

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tìm Log theo người dùng</h3>
                </div><!-- /.box-header -->

                {!! Form::open([
                   'route' => 'transfer-coin-log-search',
                   'class' => 'form-horizontal',
                   'method' => 'GET'
                ]) !!}

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('date', 'Ngày tháng', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('date', Request::get('date'), ['class' => 'form-control', 'id' => 'daterange', 'readonly' => 'readonly']) !!}
                                <span class="input-group-btn">
                                <button class="btn btn-danger" type="button"
                                        onclick="document.getElementById('daterange').value = '';"><i
                                            class="fa fa-remove"></i>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('uid', 'UID, Tên đăng nhập *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('uid', Request::get('uid'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('type', 'Trạng thái', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select class="form-control" name="status">
                                <option value="">Tất cả</option>
                                <option value="1" {{ Request::get('status') === '1' ? 'selected' : '' }}>Thành công
                                </option>
                                <option value="0" {{ Request::get('status') === '0' ? 'selected' : '' }}>Lỗi</option>
                            </select>
                        </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <a href="javascript:history.back();" class="btn btn-default">Quay lại</a>
                    {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
                </div><!-- /.box-footer -->

                {!! Form::close() !!}
            </div>


    @if(isset($logs) && !empty($logs))

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Log chuyển Coin</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="user-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Game</th>
                                <th>Máy chủ</th>
                                <th>Số Coin</th>
                                <th>Trạng thái</th>
                                <th>Mã giao dịch</th>
                                <th>Ngày tháng</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $log)
                                <tr>                                  
                                    <td>{{ $log->id }}</td>
                                    <td>{{ !empty($log->name) ? $log->name : 'N/A' }}</td>
                                    <td>{{ !empty($log->servername) ? $log->servername : 'N/A' }}</td>
                                    <td>{{ number_format($log->coin, 0, ',', '.') }}</td>
                                    <td>{{ $log->status == 1 ? 'Thành công' : 'Lỗi' }}</td>
                                    <td>{{ $log->trans_id }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($log->request_time)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {!! $logs->appends([
                            'uid' => Request::get('uid'),
                            'date' => Request::get('date'),
                            'status' => Request::get('status'),
                        ])->render() !!}
                    </div>

                </div>

    @endif
@endsection