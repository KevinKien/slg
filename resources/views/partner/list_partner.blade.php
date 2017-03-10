@extends('app')

@section('htmlheader_title')
    Trang liệt kê danh sách đối tác
@endsection
@section('contentheader_title')
    Danh sách đối tác

    <div class="box-tools pull-right col-md-3">
        <a class="btn btn-primary btn-block margin-bottom" href="/partner/add">THÊM</a>
    </div>
@endsection
@section('main-content')
    @if ( Session::has('flash_message') )

        <div class="alert {{ Session::get('flash_type') }}">
            <h3>{{ Session::get('flash_message') }}</h3>
        </div>

    @endif
    <div class="row">


        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">List</h3>
                    <div class="box-tools pull-right col-md-3" >
                        {!! Form::open([
                              'route' => 'partner.search',
                              'class' => 'form-horizontal',
                              'method' => 'get'
                           ]) !!}
                        <div class="form-group" >
                            <div class="col-sm-9">
                                <input id='searchname' style="font-size:small; height: 30px;" class='form-control' type='text'name='searchname' value="<?php echo isset($_GET['searchname'])?$_GET['searchname']:'';?>" >
                            </div>
                        </div>
                    </div>
                    <div class="box-tools pull-right col-md-1">
                        {!! Form::submit('Search', ['class' => 'btn btn-info pull-right btn-sm']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class='table table-hover table-striped'>
                            <thead>
                            <tr>
                                <th><input type="checkbox"  id="bulkDelete"  /></th>
                                <th>STT</th>
                                <th></th>
                                <th>PARTNER_ID</th>
                                <th>TÊN ÐỐI TÁC</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=0;
                            $out='';
                            foreach($partner as $row){
                                $i=$i+1;
                                if(isset($_GET['page'])){
                                    $tt=$_GET['page']*10-10+$i;
                                }
                                if(!isset($_GET['page'])){
                                    $tt=$i;
                                }
                                $out.="<tr>
                            <td><input type='checkbox'  class='deleteRow' value='".$row->partner_id."' /> </td>
                            <td>".$tt."</td>
                            <td class='mailbox-star'><a href='#'><i class='fa text-yellow fa-star'></i></a></td>
                            <td>".$row->partner_id."</td>
                            <td><a href='/partner/edit?partnerid=".$row->partner_id."'>".$row->partner_name."</a></td>
                        </tr>";
                            }
                            print($out);
                            ?>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <div class="btn-group">
                            <button  class="btn btn-default btn-sm" onclick='return confirmSubmit()'><i class="fa fa-trash-o"></i></button>
                        </div><!-- /.btn-group -->
                        <button id="refresh" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                        <div class="pull-right">
                            <?php echo $partner->render();  ?>
                        </div><!-- /.pull-right -->
                    </div>
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
    <script LANGUAGE="JavaScript">
        function confirmSubmit()
        {
            var agree=confirm("Bạn có muốn xóa mục này không?");
            if (agree){
                if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
                    var ids = [];
                    $('.deleteRow').each(function(){
                        if($(this).is(':checked')) {
                            ids.push($(this).val());
                        }
                    });
                    var ids_string = ids.toString();  // array to string conversion
                    $.ajax({
                        type: "POST",
                        url: '{{url()}}/partner1',
                        data: {data_ids:ids_string},
                        success: function(result) {
//                            alert("a");
                            location.reload();
                            //dataTable.draw(); // redrawing datatable
                        },
                        async:false
                    });
                }
                return true ;}
            else{
                return false ;}
        }
    </script>
@endsection
@section('js-current')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#bulkDelete").on('click',function() { // bulk checked
                var status = this.checked;
//                alert("a");
                $(".deleteRow").each( function() {
                    $(this).prop("checked",status);
                });
            });

            $('#refresh').on("click", function(){ // triggering delete one by one
                location.reload();
            });
        } );


    </script>
@endsection