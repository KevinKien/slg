@extends('app')

@section('htmlheader_title', 'Giao dịch thẻ cào nghi vấn')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Giao dịch thẻ cào nghi vấn</h3>
                    @if(isset($logs) && count($logs) > 0)
                        <div class="pull-right">
                            <button class="btn btn-sm btn-primary" type="button"
                                    onclick="window.location = '{{ route('card-pending.update') }}'">Cập nhật trạng thái
                            </button>
                        </div>
                    @endif
                </div>
                @if(isset($logs))
                        <!-- /.box-header -->
                <div class="box-body">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Mã giao dịch</th>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th>Mã thẻ</th>
                            <th>Số Serial</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tháng</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $paydirect = new \App\Helpers\Payments\PayDirect(); ?>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->trans_id }}</td>
                                <td>{!! link_to_route('user.edit', $log->user->name, ['id' => $log->uid]) !!}</td>
                                <td>{{ strtoupper($log->card_type) }}</td>
                                <td>{{ strtoupper($log->card_code) }}</td>
                                <td>{{ strtoupper($log->card_seri) }}</td>
                                <td>{{ number_format($log->amount, 0, ',', '.') }}</td>
                                <?php $response = json_decode($log->response, true) ?>
                                <td>{{ $paydirect::getMessage($response['status']) . ' (' . $response['status'] . ')' }}</td>
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