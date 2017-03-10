@extends('app')

@section('htmlheader_title', 'Tra cứu giao dịch thẻ cào')

@section('main-content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Tra cứu giao dịch thẻ cào</h3>
        </div>
        {!! Form::open([
               'route' => 'scratch-card-transaction',
               'class' => 'form-horizontal',
               'method' => 'POST'
            ]) !!}

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('card_code', 'Mã thẻ', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">
                    {!! Form::text('card_code', old('card_code'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('card_seri', 'Số Serial', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">
                    {!! Form::text('card_seri', old('card_seri'), ['class' => 'form-control']) !!}
                </div>
            </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="javascript:history.back();" class="btn btn-default">Back</a>
            {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
        </div><!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
    @if(!is_null($logs) || !empty($paygate_log))
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Danh sách giao dịch</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nguồn</th>
                        <th>Mã giao dịch</th>
                        <th>Người dùng</th>
                        <th>Loại thẻ</th>
                        <th>Mã thẻ</th>
                        <th>Số Serial</th>
                        <th>Mệnh giá</th>
                        <th>Ngày tháng</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!is_null($logs) && !$logs->isEmpty())
                        <?php
                        $paydirect = new \App\Helpers\Payments\PayDirect();
//                        $log_tids = [];
                        ?>
                        @foreach($logs as $log)
                            <tr>
                                <th scope="row">Database</th>
                                <td>{{ $log->trans_id }}</td>
                                <td>{!! link_to_route('user.edit', $log->user->name, ['id' => $log->uid]) !!}</td>
                                <td>{{ strtoupper($log->card_type) }}</td>
                                <td>{{ strtoupper($log->card_code) }}</td>
                                <td>{{ strtoupper($log->card_seri) }}</td>
                                <td>{{ number_format($log->amount, 0, ',', '.') }}</td>
                                <?php
                                    $response = json_decode($log->response, true);
//                                    if ($response['status'] != '01')
//                                    {
//                                        $log_tids[] = $log->trans_id;
//                                    }
                                 ?>
                                <td>{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
                                <td>{{ $paydirect::getMessage($response['status']) . ' (' . $response['status'] . ')' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th scope="row" colspan="9">Database</th>
                        </tr>
                    @endif
                    <tr>
                        @if(!empty($paygate_log))
                            <th scope="row">PayDirect</th>
                            <td>{{ $paygate_log[0] }}</td>
                            <td>N/A</td>
                            <td>{{ strtoupper($paygate_log[1]) }}</td>
                            <td>{{ strtoupper($paygate_log[2]) }}</td>
                            <td>{{ strtoupper($paygate_log[3]) }}</td>
                            <td>{{ $paygate_log[4] }}</td>
                            <td>{{ $paygate_log[5] }}</td>
                            <td>{{ $paygate_log[7] }}</td>
                        @else
                            <th scope="row" colspan="9">PayDirect</th>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    @endif
@endsection