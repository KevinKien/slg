@extends('app')
@section('htmlheader_title')
    Trang tra cứu log add coins
@endsection
@section('contentheader_title','Log Add Coins')
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
                        <form role="form" method="post" name="" action="/add-coin-log">
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
                           
                            <div class="box-footer">
                                <button class="btn btn-primary" type="submit">Tìm</button>
                            </div>
                        </form>
                        <div class="box-header with-border">
                            <h3 class="box-title">Kết quả</h3>
                        </div>                            
                        <?php
                            $total_amounts = number_format($total_amount, 0, '.', ',');
                        ?>
                            Tổng số giao dịch : <b><?php print $count;?> GD</b><br>
                            Tổng doanh thu : <b><?php print $total_amounts; ?> VND</b>
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
                                <th>TK admin</th>
                                <th>TK người dùng</th>
                                <th>Số tiền</th>
                                <th>Số coins</th>
                                <th>Mã giao dịch</th>
                                <th>Thời gian</th>'                                                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $i=0;
                            $out='';
                            $amount=0;
                            if(is_array($result)){
                            foreach($result as $row){                             
                             $i++;
                             $tt=$page*10-10+$i;
                             $amount=$row->amount;
                             if(!isset($_GET['type'])){
                                 $out.="<tr>
                                            <td>".$i."</td>
                                            <td>".$row->admin."</td>
                                            <td>".$row->user."</td>
                                            <td>".$row->amount."</td>
                                            <td>".$row->coins."</td>
                                            <td>".$row->trans_id."</td>                                                                                       
                                            <td>".$row->created_at."</td>
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
