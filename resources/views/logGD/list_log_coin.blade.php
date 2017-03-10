@extends('app')
@section('htmlheader_title')
    Trang thống kê coin User
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
                            <form role="form" method="post" name="" action="/log_coin">
                                <input type="hidden" name="_token" value="">
                                <div class="form-group">
                                    <label>Date :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="bydate" name = "bydate" value="<?php
                                        $date_to = isset($_GET['bydate']) ? date('d-m-Y', $_GET['bydate']):  date('d-m-Y', time());
                                        print $date_to;
                                        ?>" />
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->


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
                                <th>Người dùng</th>
                                <th>Số thẻ nạp</th>
                                <th>Loại thẻ</th>
                                <th>Mệnh giá</th>
                                <th>Tổng</th>
                                <th>Số tiền trong ví</th>
                                <th>Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $i=0;


                            if(is_array($logcoin)){?>
                            @foreach($logcoin as $row)
                                <?php
                                $total=$row->Numbertag*$row->amount;
                                $time=date('d/m/Y', strtotime($row->created_at));
                                $response_html = 'false';
                                if (is_array($logtype)) {
                                    $arraylog = array();
                                    foreach($logtype as $type){
                                        if($row->uid == $type->uid && $row->amount == $type->amount){
                                            $arraylog[] = (array)$type;
                                        }
                                    }
                                    $response = '<pre>' . var_export($arraylog, true) . '</pre>';
                                    $response_html = 'true';
                                }
                                ?>
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->Numbertag}}</td>
                                    <td><a tabindex="0" class="btn btn-xs btn-info" role="button" data-toggle="popover"
                                           data-html="{{ $response_html }}"
                                           title="LogCoin"
                                           data-content="{{ $response }}">Show</a>
                                    </td>
                                    <td>{{number_format($row->amount, 0, '.', ',')}}</td>
                                    <td>{{number_format($total, 0, '.', ',')}}</td>
                                    <td>{{$row->coins}}</td>
                                    <td>{{$time}}</td>
                                </tr>
                            @endforeach
                            <?php }
                            ?>
                            </tbody>
                        </table>
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
            $('#bydate').datetimepicker({
                format: 'DD-MM-YYYY',
                maxDate: moment()
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
    </script>
    <style>
        .popover-content{
            height: 250px;
            overflow-y: scroll;
        }
    </style>
@endsection