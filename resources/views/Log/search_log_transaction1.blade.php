@extends('app')
@section('htmlheader_title')
    Trang tra cứu log giao dịch
@endsection
@section('contentheader_title','Log Giao Dịch')
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
                        <form role="form" method="post" name="" action="/logGD1">
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
                                <label> UserName: </label></br>
                                <input type="text" name="username" value="<?php if(isset($_GET['username']) && $_GET['username'] != NULL){ echo $_GET['username']; } ?>"/>
                            </div>
                            <div class="form-group">
                                <label> Serial: </label></br>
                                <input type="text" name="serial" value="<?php if(isset($_GET['serial']) && $_GET['serial'] != NULL){ echo $_GET['serial']; } ?>"/>
                            </div>
                            <div class="form-group">
                                <label> Mã giao dịch: </label></br>
                                <input type="text" name="transaction_id" value="<?php if(isset($_GET['transaction_id']) && $_GET['transaction_id'] != NULL){ echo $_GET['transaction_id']; } ?>"/>
                            </div>
                            <div class="form-group">
                                <label> Chọn loại giao dịch: </label></br>
                                <select id='card_type' class='form-control' name='card_type'> 
                                    <option value=''>Tất cả</option>
                                    <option value='VT'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'VT') {?> selected="selected" <?php } ?> >Viettel</option>
                                    <option value='MOBI'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'MOBI') {?> selected="selected" <?php } ?> >Mobilefone</option>
                                    <option value='VINA'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'VINA') {?> selected="selected" <?php } ?> >VinaPhone</option>                                    
                                    <option value='CYBERPAY'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'CYBERPAY') {?> selected="selected" <?php } ?> >Cyberpay</option>
                                    <option value='VTC'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'VTC') {?> selected="selected" <?php } ?> >VTC Vcoin</option> 
                                    <option value='GATE'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'FPT') {?> selected="selected" <?php } ?> >FPT Gate</option> 
                                    <option value='MGC'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'MGC') {?> selected="selected" <?php } ?> >MegaCard</option> 
                                    <option value='atm'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'atm') {?> selected="selected" <?php } ?> >ATM</option>   
                                    <option value='visa'<?php if(isset($_GET['card_type']) && $_GET['card_type'] == 'visa') {?> selected="selected" <?php } ?> >VISA</option>   
                                </select>
                            </div>
                            <div class="form-group">
                                <label> Trạng thái: </label></br>
                                <select id='status' class='form-control' name='status'>                                     
                                    <option value=''>Tất cả</option>
                                    <option value='success'<?php if(isset($_GET['status']) && $_GET['status'] == 'success') {?> selected="selected" <?php } ?> >Thành công</option>
                                    <option value='fail'<?php if(isset($_GET['status']) && $_GET['status'] == 'fail') {?> selected="selected" <?php } ?> >Thất bại</option>                                                                         
                                </select>
                            </div>
                            <div class="form-group">
                                <label> Mệnh giá: </label></br>
                                <select id='amount' class='form-control' name='amount'> 
                                    <option value=''>Tất cả</option>
                                    <option value='10000'<?php if(isset($_GET['amount']) && $_GET['amount'] == '10000') {?> selected="selected" <?php } ?> >10.000</option>
                                    <option value='20000'<?php if(isset($_GET['amount']) && $_GET['amount'] == '20000') {?> selected="selected" <?php } ?> >20.000</option>
                                    <option value='50000'<?php if(isset($_GET['amount']) && $_GET['amount'] == '50000') {?> selected="selected" <?php } ?> >50.000</option>                                    
                                    <option value='100000'<?php if(isset($_GET['amount']) && $_GET['amount'] == '100000') {?> selected="selected" <?php } ?> >100.000</option>
                                    <option value='200000'<?php if(isset($_GET['amount']) && $_GET['amount'] == '200000') {?> selected="selected" <?php } ?> >200.000</option> 
                                    <option value='500000'<?php if(isset($_GET['amount']) && $_GET['amount'] == '500000') {?> selected="selected" <?php } ?> >500.000</option>                                     
                                </select>
                            </div>
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
                                <th>UserName</th>
                                <th>Serial</th>
                                <th>TID</th>
                                <th>Loại</th>
                                <th>Số tiền</th>
                                <th>Số Coin</th>
                                <th>Trạng thái</th>                                            
                                <th>Thời gian</th>                                                             
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
                                            <td>".$row->name."</td>
                                            <td>".$row->card_seri."</td>
                                            <td>".$row->trans_id."</td>
                                            <td>".$row->card_type."</td>                                           
                                            <td>".number_format($amount, 0, '.', ',')."</td> 
                                            <td>".$row->coin."</td>
                                            <td>".$row->payment_status."</td>
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
