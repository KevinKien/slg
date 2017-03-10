@extends('app')

@section('htmlheader_title')
    Trang liệt kê danh sách vòng quay
@endsection
@section('contentheader_title')
    Danh sách vòng quay
    <div class="box-tools pull-right col-md-3">
        <a class="btn btn-primary btn-block margin-bottom" href="/wheel/add">THÊM</a>
    </div>
@endsection
@section('main-content')

    <div class="row">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body no-padding" >

                    <div class="table-responsive mailbox-messages">
                        <table id="employee-grid" class='table table-hover table-striped'>
                            <thead>
                            <tr>
                                <th><input type="checkbox"  id="bulkDelete"  /></th>
                                <th>STT</th>
                                <th>Tên Sự Kiện</th>
                                <th>Sử dụng</th>
                                <th>Thêm giftcode</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=0;
                            $out='';

                            foreach($wheel as $row){
                                $i++;
                                if(isset($_GET['page'])){
                                    $tt=$_GET['page']*10-10+$i;
                                }
                                if(!isset($_GET['page'])){
                                    $tt=$i;
                                }

                                $wheeluse = $row->is_use;
                                if($wheeluse==1){
                                    $wheeluse="Yes";
                                }elseif($wheeluse==0){
                                    $wheeluse="No";
                                }
                                $out.="<tr>
                        <td><input type='checkbox'  class='deleteRow' value='".$row->id."' /> </td>
                        <td>".$tt."</td>
                        <td><a href='/wheel/edit?id=".$row->id."'>".$row->event."</a></td>
                        <td>".$wheeluse."</td>
                        <td><a href='/wheel/addgift?id=".$row->id."' class='label label-success'>".'Add'."</a></td>
                        </tr>";
                            }
                            print $out;
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
            var agree=confirm("Bạn có muốn xóa mục này không? Nếu xóa thì dữ liệu xẽ bị mất");
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
                        url: '{{url()}}/wheel3',
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