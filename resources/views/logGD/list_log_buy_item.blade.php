@extends('app')
@section('htmlheader_title')
    Trang thống kê giao dịch Zing & Soha
    @endsection
    @section('contentheader_title','Giao dịch Zing & Soha')
    @section('css-current')
            <!-- iCheck -->
    <link href="//id.slg.vn/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap time Picker -->
    <link href="//id.slg.vn/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
    <link href="//id.slg.vn/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>

@endsection

@section('main-content')
    <div class="box">
        <div class="row">
            <div class="col-md-3" >
                <div class="box box-solid">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Select option</h3>
                        </div>
                        <div class="box-body">
                            <form role="form" method="post" name="" action="/log_buy_item">
                                <input type="hidden" name="_token" value="">
                                <div class="form-group">
                                    <div id="select_box">
                                        <div>
                                            <label>Select App</label>
                                        </div>
                                        <select id='partner'  name='partner' class="form-control" onchange="fetch_select(this.value);">
                                            <?php
                                            $option = '';
                                            foreach($listpart as $name){
                                                if($name->describe == $partner1){
                                                    $option.="<option value='$name->describe' style='font-size:small' selected='selected'>".$name->describe."</option>";
                                                }else{
                                                    $option.="<option style='font-size:small' value='$name->describe'>".$name->describe."</option>";}
                                            }
                                            print $option;
                                            ?>
                                        </select>
                                        <div>
                                            <label>Select Game</label>
                                        </div>
                                        <select class="form-control" id="new_select" name='new_select'>
                                            <?php
                                            $option = '';
                                            foreach($listgame as $row){
                                                if($row->cpid == $part){
                                                    $option.="<option value='$row->cpid' style='font-size:small' selected='selected'>".$row->cp_name."</option>";
                                                }else{
                                                    $option.="<option style='font-size:small' value='$row->cpid'>".$row->cp_name."</option>";}
                                            }
                                            print $option;
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Date from:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date-from" name = "date-from" value="<?php
                                        $date_from = isset($_GET['datefrom']) ? date('d-m-Y', $_GET['datefrom']):  date('d-m-Y', time());
                                        print $date_from;
                                        ?>" />
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                                <div class="form-group">
                                    <label>Date to:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date-to" name="date-to" value="<?php
                                        $date_to = isset($_GET['dateto']) ? date('d-m-Y', $_GET['dateto']):  date('d-m-Y', time());
                                        print $date_to;
                                        ?>"/>
                                    </div><!-- /.input group -->
                                </div>
                                <div class="form-group">
                                    <label> Userid: </label></br>
                                    <input type="text" class="form-control" name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:'';?>" />
                                </div>

                                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit">Tìm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9" >
                <div class="box box-primary">
                    <div id="chartmonitor">
                        <table class='table table-hover table-striped'>
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Server</th>
                                <th>UserID</th>
                                <th>Số tiền</th>
                                <th>Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=0;
                            $i1=0;
                            if(is_array($results)){?>
                            @foreach($results as $row)
                                <?php
                                if($row->item_price>0&$row->userid!=''){
                                $i++;
                                $tt=$page*10-10+$i;
                                ?>
                                <tr>
                                    <td>{{$tt}}</td>
                                    <td>{{$row->server_id}}</td>
                                    <td>{{$row->userid}}</td>
                                    <td>{{number_format($row->item_price, 0, '.', ',')}}</td>
                                    <td>{{$row->request_time}}</td>
                                </tr>
                                <?php
                                }
                                ?>
                            @endforeach
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        {!! $paginator_html !!}
                    </div>
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
        });
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

        function fetch_select(val)
        {

            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '{{url()}}/log_buy_item_game',
                data: {data_describe:val},
                success: function (response) {
                    var $options_cpid = '';
//                    alert(response);
                    $.each(response, function(index, element) {
                        $options_cpid += '<option value="' + element.cpid +'">' + element.cp_name + '</option>';
                    });
                    $('#new_select').html($options_cpid);
                }
            });
        }

    </script>
    <style>
        .popover-content{
            height: 250px;
            overflow-y: scroll;
        }
    </style>
@endsection