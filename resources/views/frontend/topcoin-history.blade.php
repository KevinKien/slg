@extends('frontend.transaction-history-layout')

@section('transaction-history')
    <table class="table table-striped table-hover table-condensed">
        <thead>
        <tr>
            {{--<th>ID</th>--}}
            <th>Game</th>
            <th>Máy chủ</th>
            <th>Số Coin</th>
            <th>Trạng thái</th>
            <th>Ngày tháng</th>
        </tr>
        </thead>
        <tbody>
        @if($logs->isEmpty())
            <tr>
                <td colspan="5">Không có giao dịch nào trong vòng 3 tháng gần đây.</td>
            </tr>
        @else
            @foreach($logs as $log)
                <tr>
                    {{--<td>{{ $log->id }}</td>--}}
                    <td>{{ !empty($log->name) ? $log->name : 'N/A' }}</td>
                    <td>{{ !empty($log->servername) ? $log->servername : 'N/A' }}</td>
                    <td>{{ number_format($log->coin, 0, ',', '.') }}</td>
                    <td>{{ $log->status == 1 ? 'Thành công' : 'Lỗi' }}</td>
                    <td>{{ date('d/m/Y H:i:s', strtotime($log->request_time)) }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection

@section('transaction-history-pagination')
    @if(!$logs->isEmpty())
        <div class="panel-footer">{!! $logs->render() !!}</div>
    @endif
@endsection