@extends('app')

@section('htmlheader_title', 'Log Laravel')

@section('css-current')
    {!! HTML::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .popover-content {
            word-wrap: break-word;
        }

        #table-log td {
            word-break: break-all;
        }
    </style>
@endsection

@section('js-current')
    {!! HTML::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! HTML::script('plugins/datatables/dataTables.bootstrap.min.js') !!}

    <script>
        $(function(){
            
            $('#table-log').DataTable({
                "order": [[ 1, "desc" ]],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                },
                "fnDrawCallback": function ( oSettings ) {
                  $('[data-toggle="popover"]').popover(); 
                }
            });

            $('#table-log').on('click', '.expand', function(){
                $('#' + $(this).data('display')).toggle();
            });

            $('#delete-log').click(function(){
                return confirm('Are you sure?');
            });

            $('#l').change(function(){
                window.location = "{{ route('laravel-log') }}?l=" + $(this).val();
            });

            $('[data-toggle="popover"]').popover(); 
            
            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });
        });
    </script>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Select log</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('l', 'Log *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <select id="l" class="form-control">
                                @foreach ($files as $file)
                                    <option value="{{ base64_encode($file) }}" {{ ($current_file == $file) ?  'selected=selected' : '' }}>{{ $file }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <a id="delete-log" class="btn btn-danger" href="?del={{ base64_encode($current_file) }}"><span class="fa fa-remove"></span> Delete cache</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Log Laravel</h3>
                </div>
                @if(!empty($logs))
                        <!-- /.box-header -->
                <div class="box-body">
                    <table id="table-log" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Level</th>
                            <th>Date</th>
                            <th>Server</th>
                            <th>URL</th>
                            <th>User</th>
                            <th>Content</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $key => $log)
                            <tr>
                                <td class="text-{{ $log['level_class'] }}"><span class="fa fa-{{ $log['level_img'] }}" aria-hidden="true"></span> &nbsp;{{ $log['level'] }}</td>
                                <td class="date">{{ date('d-m-Y H:i:s', strtotime($log['date'])) }}</td>
                                <td class="server">{{ $log['server'] }}</td>
                                <td class="url">
                                    @if ($log['url'] !== '')
                                        <?php $url_arr = parse_url($log['url']) ?>
                                        <a tabindex="0" class="btn btn-xs btn-info" role="button" data-toggle="popover"
                                           data-html="true"
                                           title="URL"
                                           data-content="{{ $log['url'] }}">{{ $url_arr['host'] }}</a>
                                    @endif
                                </td>
                                <td class="user">{!! link_to_route('user.edit', $log['user_name'], ['id' => $log['user_id']]) !!}</td>
                                <td class="text">
                                    @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs" data-display="stack{{ $key }}"><span class="fa fa-search"></span></a>@endif
                                    {{ $log['text'] }}
                                    @if (isset($log['in_file'])) <br />{{ $log['in_file'] }}@endif
                                    @if ($log['stack']) <div class="stack" id="stack{{ $key }}" style="display: none; white-space: pre-wrap;">{{ trim($log['stack']) }}</div>@endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
                @endif
            </div>
        </div>
    </div>
@endsection