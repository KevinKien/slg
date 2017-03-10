@extends('app')

@section('htmlheader_title', 'Biểu đồ doanh thu')
@section('css-current')
{!! HTML::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
<!-- Bootstrap time Picker -->
{!! HTML::style('plugins/daterangepicker/bootstrap-timepicker.min.css') !!}
{!! HTML::style('plugins/daterangepicker/bootstrap-datetimepicker.min.css') !!}
{!! HTML::style('css/select2.min.css') !!}
@endsection
@section('main-content')
    <div class="box">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-solid">
                    <!--=================================-->
                    <div class="box box-warning">
                        @include('partials.filterbar')
                    </div>
                    <!--=================================-->
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div id="chartmonitor">

                    </div>
                </div>
                <?php
                if(Auth::user()->is('administrator|deploy')){
                ?>
                <div class="row" style="margin: 0 0 0 5px;">
                    <div class="box-tools pull-right col-md-2">
                        @if(isset($_GET['date-from']))
                            <a class="btn btn-primary" href="<?php echo "//".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];  ?>&xlsexport=reportrevenue_from<?php echo $datefromday.'-'.$datetoday?>" role="button">Export to excel</a>
                        @endif
                    </div>
                </div>
                <?php } ?>
                <div class="row" style="overflow-x: scroll; margin: 5px 0 0 1px;">

                    <table class="table table-bordered">
                        <thead>
                        <tr class="info">
                            <th>#</th>
                            @foreach ($xcols as $key => $row)<th>{{$row}}</th>@endforeach
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
            </div>

        </div>

    </div>



@endsection
@section('js-current')
    <script type="text/javascript">

        $(function () {
            //Date range picker
            $('#date-from').datetimepicker({
                format: 'DD-MM-YYYY',
                maxDate: moment()
            });
            $('#date-to').datetimepicker({
                format: 'DD-MM-YYYY',
                maxDate: moment()
            });
            $("#date-from").on("dp.change", function (e) {
                $('#date-to').data("DateTimePicker").minDate(e.date);
            });
            $("#date-to").on("dp.change", function (e) {
                $('#date-from').data("DateTimePicker").maxDate(e.date);
            });
            $('#chartmonitor').highcharts({
                title: {
                    text: 'Doanh thu tổng các ngày: {{ number_format($total_revenue, 0, ',', '.')  }}',
                    x: -20 //center
                },
                subtitle: {
                    text: 'Game:App',
                    x: -20
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

            //Initialize Select2 Elements
    $(".select2").select2();
    $("#select_partner").change(function() {
        var token = $('input[name="_token"]').val();
        var partner_id = $('#select_partner').val();
            if(partner_id >0){
                $.ajax({
                    url: '{{url()}}/daulog',
                    type: 'post',
                    data: "command=appid" + "&partner_id="+ partner_id + "&_token=" + token
                })
                .done(function(data) {
                        var $options_appid = '';
                                $options_appid += '<option value="0">Selet app</option>';
                                $.each(JSON.parse(data), function(i, item) {
                                           $options_appid += '<option value="' + item.app_id + '">' + item.name + '</option>';
                                 })
                                 $("select#select_app").html($options_appid);
                                 $('select#select_app').removeAttr("disabled");
                                 $('select#select_cp').val('');

                })
                .fail(function() {
                        alert("error");
                 });
            }else{
                $('select#select_app').prop('selectedIndex',0);
                $('select#select_app').prop('disabled', 'disabled');
                $('select#select_cp').val('');
                $('select#select_cp').prop('disabled', 'disabled');
                $('.submit').prop('disabled', 'disabled');
            }

    });
    ////////////
    $(".partner_id").change(function() {
        if (!$(this).is(':checked')) {
            $('select option[alt='+$(this).val()+']').removeAttr("selected");
            $(".select2").select2();
        }
        if ($(this).is(':checked')) {
            $('select option[alt='+$(this).val()+']').attr("selected","selected");
            $(".select2").select2();
        }
    });
    $(".app_id").change(function() {
//        if (!$(this).is(':checked')) {
//            $('.cp_id[rel='+$(this).val()+']').attr("disabled", true);
//        }
        if ($(this).is(':checked')) {
            $('.cp_id[rel='+$(this).val()+']').removeAttr("disabled");
            $('.cp_id[rel='+$(this).val()+']').prop('checked', true);
        }
    });
    //////////////////////
    $("#select_app").change(function () {
        var token = $('input[name="_token"]').val();
        var app_id = $('#select_app').val();
        $.ajax({
            url: '{{url()}}/daulog',
            type: 'post',
            data: "command=partner" + "&app_id="+ app_id + "&_token=" + token
        })
                .done(function (data) {
                    var $options_cpid = '';
                    $(":checkbox").parent().hide();
                    $.each(JSON.parse(data), function (i, item) {
                        $options_cpid += '<option value="' + item.cpid + '" selected alt="'+ item.partner_id +'" rel="'+ item.app_id +'">' + item.cp_name + '</option>';
                        $(":checkbox[value="+ item.partner_id +"]").prop("checked","true");
                        $(":checkbox[value="+ item.partner_id +"]").parent().show();
                    })
                    $("select#select_cp").html($options_cpid);
                    $(".select2").select2();

                })
                .fail(function () {
                    alert("error");
                });
    });
    });
</script>

<script src="{{ asset('/js/highcharts.js') }}"></script>
<script src="{{ asset('/js/exporting.js') }}"></script>
<script src="{{ asset('/js/select2.full.min.js') }}"></script>
@endsection