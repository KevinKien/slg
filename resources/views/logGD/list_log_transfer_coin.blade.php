@extends('app')
@section('htmlheader_title')
    Trang thống kê transfer coin User
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
                            <form role="form" method="post" name="" action="/log_transfer_coin">
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
                                <th>Số lần chuyển coin</th>
                                <th>Coin</th>
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
                                $total=$row->Numbercoin*$row->coin;
                                $time=date('d/m/Y', strtotime($row->request_time));

                                ?>
                                <tr>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->Numbercoin}}</td>
                                    <td>{{$row->coin}}</td>
                                    <td>{{$total}}</td>
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

    </script>
    <style>
        .popover-content{
            height: 250px;
            overflow-y: scroll;
        }
    </style>
@endsection