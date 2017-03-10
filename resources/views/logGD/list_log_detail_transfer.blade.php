@extends('app')
@section('htmlheader_title')
    Trang thống kê giao dịch User
    @endsection
    @section('contentheader_title','Danh thu Fpay')
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
                            <form role="form" method="post" name="" action="/log_detail_transfer">
                                <input type="hidden" name="_token" value="">
                                <div class="form-group">
                                    <div id="select_box">
                                        <div>
                                            <label>Select App</label>
                                        </div>
                                        <select id='game1'  name='game1' class="form-control" onchange="fetch_select(this.value);">
                                            <?php
                                            $option = '';
                                            foreach($listapp as $appid){
                                                if($appid->id == $game1){
                                                    $option.="<option value='$appid->id' style='font-size:small' selected='selected'>".$appid->name."</option>";
                                                }else{
                                                    $option.="<option style='font-size:small' value='$appid->id'>".$appid->name."</option>";}
                                            }
                                            print $option;
                                            ?>
                                        </select>
                                        <div>
                                            <label>Select Partner</label>
                                        </div>
                                        <select class="form-control" id="new_select" name='new_select'>
                                            <?php
                                            $option = '';
                                            foreach($listpart as $row){
                                                if($row->describe == 'Fpay'){
                                                if($row->app_id == '18903329'){
                                                        $option ="<option value='18903329' style='font-size:small' selected='selected'>".'Soul SLG'."</option>";
                                                }elseif($row->app_id == '18903334'){
                                                        $option ="<option value='18903334' style='font-size:small' selected='selected'>".'Tiểu Long SLG'."</option>";
                                                }elseif($row->app_id == $part){
                                                    $option.="<option value='$row->app_id' style='font-size:small' selected='selected'>".$row->cp_name."</option>";
                                                }else{
                                                    $option.="<option style='font-size:small' value='$row->app_id'>".$row->cp_name."</option>";}
                                                }else{
                                                    if($row->cpid == $part){
                                                        $option.="<option value='$row->cpid' style='font-size:small' selected='selected'>".$row->cp_name."</option>";
                                                    }else{
                                                        $option.="<option style='font-size:small' value='$row->cpid'>".$row->cp_name."</option>";}
                                                }
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

                                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit">Tìm</button>
                                </div>
                            </form>
                        </div>
                        <div class="box-header with-border">
                            <h3 class="box-title">Kết quả</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            $i2=0;
                            $tongtest = 0;
                            $dttest=0;
                            $i1=0;
                            $tong=0;
                            $dt=0;
                            $i3=0;
                            $tongdtt=0;
                            $dtt=0;
                            $i4=0;
                            $gdtc=0;
                            $tonggdtc = 0;
                            $i5=0;
                            $gdtb=0;
                            $tonggdtb = 0;
                            $chanel = '';
                            if(is_array($data)){
                                foreach($data as $row1){
                                    if($row1->amount>0&$row1->uid!=''){
                                        if(isset($row1->channel_id)){
                                            if($row1->channel_id == 'soha'){
                                                $chanel='soha';
                                                if($row1->cpid == '300000193'){
                                                    $check = "no";
                                                    foreach($account as $rowac){
                                                        if($rowac->test_id == $row1->uid){
                                                            $check = "yes";
                                                        }
                                                    }
                                                    if($row1->status == 3){
                                                        $i4++;
                                                        $tonggdtc+=$row1->amount*100 ;
                                                        $gdtc=number_format($tonggdtc, 0, '.', ',');
                                                    }else{
                                                        $i5++;
                                                        $tonggdtb+=$row1->amount*100 ;
                                                        $gdtb=number_format($tonggdtb, 0, '.', ',');
                                                    }
                                                    $i1++;
                                                    $tong+=$row1->amount*100 ;
                                                    $dt=number_format($tong, 0, '.', ',');
                                                    if( $check == "yes"){
                                                        $i2++;
                                                        $tongtest+=$row1->amount*100;
                                                        $dttest=number_format($tongtest, 0, '.', ',');}
                                                    else{
                                                        $i3++;
                                                        $tongdtt+=$row1->amount*100;
                                                        $dtt=number_format($tongdtt, 0, '.', ',');}
                                                }else{
                                                    $check = "no";
                                                    foreach($account as $rowac){
                                                        if($rowac->test_id == $row1->uid){
                                                            $check = "yes";
                                                        }
                                                    }
                                                    if($row1->status == 3){
                                                        $i4++;
                                                        $tonggdtc+=$row1->amount*1000 ;
                                                        $gdtc=number_format($tonggdtc, 0, '.', ',');
                                                    }else{
                                                        $i5++;
                                                        $tonggdtb+=$row1->amount*1000 ;
                                                        $gdtb=number_format($tonggdtb, 0, '.', ',');
                                                    }
                                                    $i1++;
                                                    $tong+=$row1->amount*1000 ;
                                                    $dt=number_format($tong, 0, '.', ',');
                                                    if( $check == "yes"){
                                                        $i2++;
                                                        $tongtest+=$row1->amount*1000;
                                                        $dttest=number_format($tongtest, 0, '.', ',');}
                                                    else{
                                                        $i3++;
                                                        $tongdtt+=$row1->amount*1000;
                                                        $dtt=number_format($tongdtt, 0, '.', ',');}
                                                }
                                            }elseif($row1->channel_id == 'zing'){
                                                $check = "no";
                                                $chanel='zing';
                                                foreach($account as $rowac){
                                                    if($rowac->test_id == $row1->uid){
                                                        $check = "yes";
                                                    }
                                                }
                                                if($row1->status == 3){
                                                    $i4++;
                                                    $tonggdtc+=$row1->amount;
                                                    $gdtc=number_format($tonggdtc, 0, '.', ',');
                                                }else{
                                                    $i5++;
                                                    $tonggdtb+=$row1->amount;
                                                    $gdtb=number_format($tonggdtb, 0, '.', ',');
                                                }
                                                $i1++;
                                                $tong+=$row1->amount;
                                                $dt=number_format($tong, 0, '.', ',');
                                                if( $check == "yes"){
                                                    $i2++;
                                                    $tongtest+=$row1->amount;
                                                    $dttest=number_format($tongtest, 0, '.', ',');}
                                                else{
                                                    $i3++;
                                                    $tongdtt+=$row1->amount;
                                                    $dtt=number_format($tongdtt, 0, '.', ',');}
                                            }
                                        }else{
                                            $chanel = 'slg';
                                            $check = "no";
                                            foreach($account as $rowac){
                                                if($rowac->test_id == $row1->uid){
                                                    $check = "yes";
                                                }
                                            }
                                            $i1++;
                                            $tong+=$row1->amount*100;
                                            $dt=number_format($tong, 0, '.', ',');
                                            if( $check == "yes"){
                                                $i2++;
                                                $tongtest+=$row1->amount*100;
                                                $dttest=number_format($tongtest, 0, '.', ',');}
                                            else{
                                                $i3++;
                                                $tongdtt+=$row1->amount*100;
                                                $dtt=number_format($tongdtt, 0, '.', ',');}
                                        }
                                    }
                                }

                            }
                            ?>
                            @if($chanel=='soha')
                                Tổng số giao dịch:<b><?php print$i1;?>GD</b><br>
                                Tổng doanh thu:<b><?php print $dt; ?>VND</b><br>
                                Tổng số giao dịch thành công:<b><?php print$i4;?>GD</b><br>
                                Tổng doanh thu thành công:<b><?php print $gdtc; ?>VND</b><br>
                                Tổng số giao dịch thất bại:<b><?php print$i5;?>GD</b><br>
                                Tổng doanh thu thất bại:<b><?php print $gdtb; ?>VND</b>
                            @elseif($chanel=='zing' or $chanel=='slg')
                            Tổng số giao dịch:<b><?php print$i1;?>GD</b><br>
                            Tổng doanh thu:<b><?php print $dt; ?>VND</b><br>
                            Tổng số giao dịch thực:<b><?php print$i3;?>GD</b><br>
                            Tổng doanh thu thực:<b><?php print $dtt; ?>VND</b><br>
                            Tổng số giao dịch test:<b><?php print$i2;?>GD</b><br>
                            Tổng doanh thu test:<b><?php print $dttest;?>VND</b><br>
                            @endif

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
                                <th>Userid</th>
                                <th>Số tiền</th>
                                <th>Trạng Thái</th>
                                
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
                                $time = 0;
                                if(is_integer($row->time)){
                                    $time=date('H:i:s d/m/Y', $row->time);
                                }else{
                                    $time = $row->time;
                                }
                                if($row->amount>0&$row->uid!=''){
                                $i++;
                                $tt=$page*10-10+$i;
                                if(isset($row->channel_id)){
                                $status;
                                if($row->status == 3){
                                    $status = "thành công";
                                }else{
                                    $status = "thất bại";
                                }
                                if($row->channel_id == 'soha'){
                                $i1++;
                                $tt1=$page*10-10+$i1;
                                if($row->cpid == '300000193'){
                                ?>
                                <tr>
                                    <td>{{$tt1}}</td>
                                    <td>{{$row->uid}}</td>
                                    <td>{{number_format($row->amount*100, 0, '.', ',')}}</td>
                                    <td>{{$status}}</td>                                    
                                    
                                    <td>{{$time}}</td>
                                </tr>
                                <?php
                                }else{
                                ?>
                                <tr>
                                    <td>{{$tt1}}</td>
                                    <td>{{$row->uid}}</td>
                                    <td>{{number_format($row->amount*1000, 0, '.', ',')}}</td>
                                    <td>{{$status}}</td>      
                                    
                                    <td>{{$time}}</td>
                                </tr>
                                 <?php
                                }}else{
                                ?>
                                <tr>
                                    <td>{{$tt}}</td>
                                    <td>{{$row->uid}}</td>
                                    <td>{{number_format($row->amount, 0, '.', ',')}}</td>
                                    <td>{{$status}}</td>      
                                    
                                    <td>{{$time}}</td>
                                </tr>
                                <?php
                                }
                                }else{
                                ?>
                                <tr>
                                    <td>{{$tt}}</td>
                                    <td>{{$row->uid}}</td>
                                    <td>{{number_format($row->amount*100, 0, '.', ',')}}</td>
                                    <td>{{$row->response}}</td>   
                                    
                                    <td>{{$time}}</td>
                                </tr>
                                <?php }}
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
                url: '{{url()}}/log_detail_transfer_partner',
                data: {data_ids:val},
                success: function (data) {
                    var $options_cpid = '';
//                    alert(response);
                    $.each(data, function(index, element) {
                        if(element.describe == 'Fpay'){
                        if(element.app_id == '18903329'){
                            $options_cpid = '<option value="' + element.app_id +'">' + 'Soul SLG' + '</option>';
                        }else if(element.app_id == '18903334'){
                            $options_cpid = '<option value="' + element.app_id +'">'+'Tiểu Long SLG'+'</option>';
                        }else{
                        $options_cpid += '<option value="' + element.app_id +'">' + element.cp_name + '</option>';}
                        }else{
                            $options_cpid += '<option value="' + element.cpid +'">' + element.cp_name + '</option>';
                        }
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