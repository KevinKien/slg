@extends('app')
@section('htmlheader_title')
    Trang thống kê doanh thu của Mwork
@endsection
@section('contentheader_title','Danh thu Mwork')

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
                        <form role="form" method="post" name="" action="/log_mwork">
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
                                <select id='game' class='form-control' name='game'>
                                   
                                <?php
                                    
                                    $option1='';
                                    foreach ($list_game1 as $value1){
                                        $option1.='<option value="'.$value1->cpid.'">'.$value1->cp_name.'</option>';
                                    }
                                    $option2='';
                                    foreach ($list_game2 as $value2){
                                        $option2.='<option value="'.$value2->cpid.'">'.$value2->cp_name.'</option>';
                                    }
                                    print $option1.$option2;
                                
                                ?>
                               
                                </select>
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
                                if($row1->amount>0&$row1->uid!=''){
                                    if($row1->response->errorCode == '200'){
                                $i1++;
                                $tong+=$row1->amount;
                                $dt=number_format($tong, 0, '.', ',');
                                }}
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
                    <table class='table table-hover table-striped'>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>UserId</th>
                                
                                <th>MỆNH GIÁ</th>
                                <th>Status</th>
                                <th>Response</th>
                                <th>THỜI GIAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            function objectToArray($d) {
                            if (is_object($d)) {
                            // Gets the properties of the given object
                            // with get_object_vars function
                            $d = get_object_vars($d);
                            }

                            if (is_array($d)) {
                            /*
                            * Return array converted to object
                            * Using __FUNCTION__ (Magic constant)
                            * for recursive call
                            */
                            return array_map(__FUNCTION__, $d);
                            }
                            else {
                            // Return array
                            return $d;
                            }
                            }
                            $i=0;
                            $out='';
                            $amount=0;
                            if(is_array($result)){
                            foreach($result as $row){
                                if($row->amount>0&$row->uid!=''){
                                 if($row->response->errorCode == '200'){
                             $i++;
                             $tt=$page*10-10+$i;
                                //if($i!=0){
                                $amount=$row->amount;
                                $response_array = objectToArray($row->response);
                                ?>
                            <tr>
                                <td>{{$tt}}</td>
                                <td>{{$row->uid}}</td>

                                <td>{{number_format($amount, 0, '.', ',')}}</td>
                                <td>{{$row->response->errorCode}}</td>
                                <td><a tabindex="0" class="btn btn-xs btn-info" role="button" data-toggle="popover" data-html="true" title="Response Content" data-content="<pre>{{var_export($response_array, true)}}</pre>" data-original-title="Response">Response</a> 
                                <td>{{$row->time}}</td>
                            </tr>
                                <?php
                                        }
                               // }  elseif ($i==0){
                                   // $out.="<tr>Không có dữ liệu</tr>";
                                }
                            }
                            }
//                            print $out;
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
      <script type="text/javascript">
function handleSelect(elm)
{
window.location = "/log_mwork?game="+elm.value;
}
</script>
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover({
                //html: true,
                container: '#chartmonitor'
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