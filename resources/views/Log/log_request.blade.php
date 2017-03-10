@extends('app')

@section('htmlheader_title', 'Request Log')

@section('js-current')
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover({
                //html: true,
                container: 'body'
            });

            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });
        })
    </script>
@endsection
@section('css-current')
    <style>
        .popover-content {
            word-wrap: break-word;
        }
    </style>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Request Log</h3>

                    <div class="pull-right">
                        <button class="btn btn-sm btn-danger" type="button"
                                onclick="window.location = '{{ route('clear-request-log') }}'">Clear All
                        </button>
                    </div>
                </div>
                @if(isset($logs))
                        <!-- /.box-header -->
                <div class="box-body">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Method</th>
                            <th>URL</th>
                            <th>Server IP</th>
                            <th>Client IP</th>
                            <th>OS</th>
                            <th>Request</th>
                            <th>Response</th>
                            <th title="Elapsed Time">ET (ms)</th>
                            <th>Error</th>
                            <th>Accessed at</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($logs as $log)
                            <?php
                            $ip_server = '';
                            $ip_client = '';

                            $ip_array = json_decode($log['ip'], true);
                            if (is_array($ip_array)) {
                                $ip_server = $ip_array['server'];
                                $ip_client = $ip_array['client'];
                            }

                            $payload = $log['response_content'];
                            $payload_html = 'false';

                            $os = '';
                            $error = '';

                            $payload_array = json_decode($log['payload'], true);
                            if (is_array($payload_array)) {
                                if (isset($payload_array['os_id'])) {
                                    if ($payload_array['os_id'] === '1') {
                                        $os = 'Android';
                                    } elseif ($payload_array['os_id'] === '2') {
                                        $os = 'iOS';
                                    }
                                }

                                $payload = '<pre>' . var_export($payload_array, true) . '</pre>';
                                $payload_html = 'true';
                            }

                            $response = $log['response_content'];
                            $response_html = 'false';

                            $response_array = json_decode($log['response_content'], true);
                            if (is_array($response_array)) {
                                $response = '<pre>' . var_export($response_array, true) . '</pre>';
                                $response_html = 'true';

                                if (isset($response_array['error_code']) && $response_array['error_code'] != 200 && isset($response_array['message'])) {
                                    $error = $response_array['message'];
                                }
                            }
                            ?>

                            <tr>
                                <td>{{ isset($log['method']) ? $log['method'] : '' }}</td>
                                <td>{{ $log['url'] }}</td>
                                <td>{{ $ip_server }}</td>
                                <td>{{ $ip_client }}</td>
                                <td>{{ $os }}</td>
                                <td><a tabindex="0" class="btn btn-xs btn-info" role="button" data-toggle="popover"
                                       data-html="{{ $payload_html }}"
                                       title="Payload"
                                       data-content="{{ $payload }}">Show</a>
                                </td>
                                <td><a tabindex="0" class="btn btn-xs btn-info" role="button" data-toggle="popover"
                                       data-html="{{ $response_html }}"
                                       title="Response Content"
                                       data-content="{{ $response }}">Show</a></td>
                                <td>{{ $log['response_time'] }}</td>
                                <td>
                                    @if($error !== '')
                                        <a tabindex="0" class="btn btn-xs btn-info" role="button" data-toggle="popover"
                                           data-html="false"
                                           title="Error Message"
                                           data-content="{{ $error }}">Show</a>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y H:i:s', $log['created_at']) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! $paginator_html !!}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection