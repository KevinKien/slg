@extends('app')

@section('htmlheader_title', 'Log giao dịch')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Log giao dịch</h3>
                </div>
                @if(isset($logs))
                        <!-- /.box-header -->
                <div class="box-body">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>UID</th>
                            <th>TID</th>
                            <th>Loại</th>
                            <th>Số tiền</th>
                            <th>Số Coin</th>
                            <th>Trạng thái</th>
                            <th>Ngày tháng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->uid }}</td>
                                <td>{{ $log->trans_id }}</td>
                                <td>{{ strtoupper($log->card_type) }}</td>
                                <td>{{ number_format($log->amount, 0, ',', '.') }}</td>
                                <td>{{ number_format($log->coin, 0, ',', '.') }}</td>
                                <td>{{ $log->payment_status }}</td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! $logs->render() !!}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection