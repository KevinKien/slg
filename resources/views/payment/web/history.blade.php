@extends('payment.web.layout_payweb')

@section('htmlheader_title')
    Lịch sử giao dịch
@endsection

@section('css-current')
    <link type="text/css" rel="stylesheet" href="{{ url('portal/css/cs.css') }}">
@endsection

@section('content')
    <section class="ContsSubBox v01_layout float_right">
        <h3 class="big floating_left">Lịch sử giao dịch</h3>
        <div class="space15"></div>
        <div class="space15"></div>
        <table width="100%" cellspacing="0" cellpadding="0" class="tableStyle01">
            <colgroup>
                <col width="23%">
                <col width="28.5%">
                <col width="28.5%">
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th>Thời gian</th>
                <th>Game</th>
                <th>Máy chủ</th>
                <th>Coin</th>
            </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ date('d/m/Y H:i:s', strtotime($log->request_time)) }}</td>
                    <td>{{ !empty($log->game->name) ? $log->game->name : '' }}</td>
                    <td>{{ !empty($log->server->servername) ? $log->server->servername : '' }}</td>
                    <td>{{ $log->coin }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endsection