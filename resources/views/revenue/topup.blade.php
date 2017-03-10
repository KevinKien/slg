@extends('app')

@section('htmlheader_title', 'Biểu đồ doanh thu Topup')

@section('css-current')
    {!! HTML::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
@endsection

@section('js-current')
    {!! HTML::script('plugins/moment.min.js') !!}
    {!! HTML::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! HTML::script('js/highcharts.js') !!}
    {!! HTML::script('js/exporting.js') !!}

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
                        startDate: moment().subtract(6, 'days'),
                        endDate: moment()
                    }
            );
        });

        $('#chartmonitor').highcharts({
            title: {
                text: 'Tổng các ngày: {{ number_format($total_revenue, 0, ',', '.')  }} VND',
                x: -20 //center
            },
            xAxis: {
                categories: [@foreach ($date_range as $key => $row)'{{$row}}', @endforeach]
            },
            yAxis: {
                title: {
                    text: 'VND'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
                    @foreach ($results as $key => $result)
                {
                    name: '{!! $results[$key]["name"] !!}',
                    data: [@foreach ($result['data'] as $row){{$row}}, @endforeach]
                },
                @endforeach
            ]
        });
    </script>
@endsection

@section('main-content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Tùy chọn</h3>
        </div><!-- /.box-header -->

        {!! Form::open([
           'route' => 'revenue-topup',
           'class' => 'form-horizontal',
        ]) !!}

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('date', 'Ngày', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-5">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <?php $date = empty($preset_date) ? old('date') : $preset_date ?>
                        {!! Form::text('date', $date, ['class' => 'form-control', 'id' => 'daterange', 'readonly' => 'readonly']) !!}
                        <span class="input-group-btn">
                                <button class="btn btn-danger" type="button"
                                        onclick="document.getElementById('daterange').value = '';"><i
                                            class="fa fa-remove"></i>
                                </button>
                                </span>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="javascript:history.back();" class="btn btn-default">Quay lại</a>
            {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
        </div><!-- /.box-footer -->

        {!! Form::close() !!}
    </div>

    @if(!empty($results))

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Biểu đồ doanh thu Topup</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="chartmonitor"></div>
            </div>

            <div class="row" style="overflow-x: scroll; margin: 5px 0 0 1px;">

                <table class="table table-bordered">
                    <thead>
                    <tr class="info">
                        <th>#</th>
                        @foreach ($date_range as $key => $row)<th>{{$row}}</th>@endforeach
                        <th>Peak</th>
                        <th>Avg</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($results as $key => $result)
                        <tr>
                            <td><?php echo $results[$key]["name"]; ?></td>
                            @foreach ($result['data'] as $row)<td>{{
                                       number_format($row, 0, '.', ',')
                                }}</td>@endforeach
                            <td>{{number_format(max($result['data']), 0, '.', ',')}}</td>
                            <td>{{number_format(round(array_sum($result['data']) / count($result['data']),2), 0, '.', ',')}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
            <!-- /.box-body -->
        </div>

    @endif
@endsection