@extends('app')
@section('htmlheader_title')
    Trang thống kê doanh thu của Zing
@endsection
@section('contentheader_title','Danh thu Zing')
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
                        <form role="form" method="post" name="" action="/log_zing">
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
                                    print$date_to;
                                    ?>"/>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                            <div class="form-group">
                                <label> Hãy chọn Game: </label></br>
                                {!! Form::select('game', $data1, $appid, ['class'=> 'form-control']) !!}

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
                        $i1=0;
                        $tong=0;
                        $dt=0;
                        if(is_array($result_total)){
                            foreach($result_total as $row1){
                                $i1++;
                                $tong+=$row1->amount;
                                if(!isset($_GET['appid'])){
                                            $dt=number_format($tong*100, 0, '.', ',');
                                        }else{
                                            if($_GET['appid']==18903324){
                                               $dt=number_format($tong*100, 0, '.', ',');
                                            }
                                            if($_GET['appid']==17054245){
                                                $dt=number_format($tong, 0, '.', ',');
                                            }
                                        }
                            }
                        }
                        ?>
                        Tổng số giao dịch:<b><?php print$i1;?>GD</b><br>
                        Tổng doanh thu:<b><?php print $dt; ?>VND</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" >
            <div class="box box-primary">
                <div id="chartmonitor">
                     <div class="table-responsive mailbox-messages"> 
                        <table class='table table-hover table-striped'>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>UserId</th>
                                    <th>Số tiền</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Server</th>
                                    <th>Mã order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=0;
                                $out='';
                                if(is_array($result)){    
                                    foreach($result as $row){
                                     $i++;
                                     $tt=$page*10-10+$i;
                                     if($i==0){
                                         $out."<tr>Không có dữ liệu!</tr>";
                                     }else{

                                     if(!isset($_GET['appid'])){
                                                $amount=$row->amount*100;
                                            }else{
                                                if($_GET['appid']==18903324){
                                                   $amount=$row->amount*100;   
                                                }
                                                if($_GET['appid']==17054245){
                                                    $amount=$row->amount;   
                                                }
                                            }
                                     $out.="<tr>
                                             <td>".$tt."</td>
                                             <td>".$row->uid."</td>
                                             <td>".number_format($amount, 0, '.', ',')."</td> 
                                             <td>".date('H:i:s d/m/Y', $row->time)."</td>
                                             <td>".$row->pay_to_game_response."</td>
                                             <td>".$row->serverid."</td>
                                             <td>".$row->orderid."</td>
                                             </tr>";
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
