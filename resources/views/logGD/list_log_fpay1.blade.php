@extends('app')
@section('htmlheader_title')
    Trang thống kê doanh thu của Fpay
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
                        <form role="form" method="post" name="" action="/log_fpay1">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label>Date from:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="date-from" name = "date-from" value="<?php
                                    $date_from = isset($_GET['dateform']) ? date('d-m-Y', $_GET['dateform']):  date('d-m-Y', time());
                                    print$date_from;
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
                            </div><!-- /.form group -->
                            <div class="form-group">
                                <label> Hãy chọn Game: </label></br>
                                {!! Form::select('game', $list_game2, $cpid, ['class'=> 'form-control']) !!}
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
                        if(is_array($result_total)){

                            foreach($result_total as $row1){
                               if($row1->amount>0&$row1->uid!=''){
                                   $check = "no";
                                   foreach($account as $rowac){
                                       if($rowac->test_id == $row1->uid){
                                           $check = "yes";
                                       }
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

                                   }

                        }  
                        ?>
                        Tổng số giao dịch:<b><?php print$i1;?>GD</b><br>
                        Tổng doanh thu:<b><?php print $dt; ?>VND</b><br>
                        Tổng số giao dịch thực:<b><?php print$i3;?>GD</b><br>
                        Tổng doanh thu thực:<b><?php print $dtt; ?>VND</b><br>
                        Tổng số giao dịch test:<b><?php print$i2;?>GD</b><br>
                        Tổng doanh thu test:<b><?php print $dttest; ?>VND</b>
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
                                <th>UserId</th>
                                
                                <th>Số tiền</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $i=0;
                            $out='';
                            $amount=0;
                           
                            if(is_array($result)){
                            foreach($result as $row){
                             if($row->amount>0&$row->uid!=''){
                             $i++;
                             $tt=$page*10-10+$i;
                             $amount=$row->amount;
                                 if(is_integer($row->time)){
                                     $time=date('H:i:s d/m/Y', $row->time);
                                     $check = "no";
                                     foreach($account as $acc){
                                     if( $acc->test_id == $row->uid){
                                     $check = "yes";
                                     }}
                                     if($check == "yes"){
                                         $out.="<tr style='color: #FF4130;'>
                                        <td>".$tt."</td>
                                        <td>".$row->uid."</td>
                                        <td>".number_format($amount, 0, '.', ',')."</td>
                                        <td>".$time."</td>
                                        </tr>";
                                     }
                                     else{
                                         $out.="<tr>
                                        <td>".$tt."</td>
                                        <td>".$row->uid."</td>
                                        <td>".number_format($amount, 0, '.', ',')."</td>
                                        <td>".$time."</td>
                                        </tr>";
                                     }
                                 }else{
                                     $check1 = "no";
                                     foreach($account as $acc){
                                         if($acc->test_id==$row->uid){
                                             $check1 = "yes";}
                                         }
                                     if($check1 == "yes"){
                                         $out.="<tr style='color: #FF4130;'>
                                        <td>".$tt."</td>
                                        <td>".$row->uid."</td>
                                        <td>".number_format($amount, 0, '.', ',')."</td>
                                        <td>".$row->time."</td>
                                        </tr>";}
                                     else{
                                         $out.="<tr >
                                        <td>".$tt."</td>
                                        <td>".$row->uid."</td>
                                        <td>".number_format($amount, 0, '.', ',')."</td>
                                        <td>".$row->time."</td>
                                        </tr>";}
                                 }
                                         //$time=$row->time;
                             }
                            }
                            }
                           
                            print $out;
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
     </script>
@endsection
