@extends('frontend.transaction-history-layout')

@section('transaction-history')
    <table class="table table-striped table-hover table-condensed">
        <thead>
        <tr>
            <th>Mã giao dịch</th>
            <th>Mã thẻ</th>
            <th>Loại</th>
            <th>Trạng thái</th>
            <th>Số tiền (VND)</th>
            <th>Số SCoin đã nạp</th>
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
                    <td>{{ $log->trans_id }}</td>
                    <td>{{ $log->card_code }}</td>
                    <td>{{ strtoupper($log->card_type) }}</td>
                    <td>{{ $log->payment_status == 'success' ? 'Giao dịch thành công.' : $log->payment_status }}</td>
                    <td>{{ is_numeric($log->amount) ? number_format($log->amount, 0, ',', '.') : 0 }}</td>
                    <td>{{ is_numeric($log->coin) ? number_format($log->coin, 0, ',', '.') : 0 }}</td>
                    <td>{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
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