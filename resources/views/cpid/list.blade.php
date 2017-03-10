@extends('app')

@section('htmlheader_title')
    Trang liệt kê danh sách cpid
@endsection
@section('contentheader_title')
    Danh sách cpid

    <div class="box-tools pull-right col-md-3">
        <a class="btn btn-primary btn-block margin-bottom" href="/cpid/add">THÊM CPID</a>
    </div>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">List</h3>
                    <div class="box-tools pull-right col-md-3" >
                        {!! Form::open([
                              'route' => 'cpid.search',
                              'class' => 'form-horizontal',
                              'method' => 'get'
                           ]) !!}
                        <div class="form-group" >
                            <div class="col-sm-9">
                                <select id='game1' class='form-control' name='game1' style="font-size:small; height: 30px;">
                                    <?php
                                    $option = '';
                                    $option.="<option value='1' style='font-size:small'>Tất cả</option>";
                                    foreach($partner as $acc){
                                        if($acc->partner_id == $game1){
                                            $option.="<option value='$acc->partner_id' style='font-size:small' selected='selected'>".$acc->partner_name."</option>";
                                        }else{
                                            $option.="<option style='font-size:small' value='$acc->partner_id'>".$acc->partner_name."</option>";}
                                    }
                                    print $option;
                                    ?>

                                </select>
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
                                <th>CPID</th>
                                <th>Tên CPID</th>
                                <th>TÊN ĐỐI TÁC</th>
                                <th>TÊN GAME</th>
                                <th>Thời gian</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=0;
                            $out='';

                            foreach($results as $row){
                                $i++;
                                if(isset($_GET['page'])){
                                    $tt=$_GET['page']*10-10+$i;
                                }
                                if(!isset($_GET['page'])){
                                    $tt=$i;
                                }
                                $out.="<tr>
                        <td><input type='checkbox'  class='deleteRow' value='".$row->cpid."' /></td>
                        <td>".$tt."</td>
                        <td class='mailbox-star'><a href='#'><i class='fa text-yellow fa-star'></i></a></td>
                        <td>".$row->cpid."</td>
                        <td><a href='cpid/edit?cpid=".$row->cpid."'>".$row->cp_name."</a></td>
                        <td>".$row->partner_name."</td>
                        <td>".$row->name."</td>
                        <td>".date('d/m/Y', $row->time_update)."</td>

                        </tr>";
                            }
                            print $out;
                            ?>
                                    <!--
            	@foreach($results as $row)
                                    <td><a href='cpid/edit?cpid=".$row->cpid."'>Sửa</a>|<a href='cpid/delete?cpid=".$row->cpid."'onclick='return confirmSubmit()'>Xóa</a></td>
                                         <tr> 1 android , 2 ios , 3 winfone
                                             <td>{{$row->cpid}}</td>
                        <td>{{$row->partner_name}}</td>
                        <td>{{$row->name}}</td>
                        <td>{{$row->ga_id}}</td>
                        <td>{{date('d/m/Y H:i:s', $row->time_update)}}</td>
                        <td><a href='cpid/edit?cpid={{$row->cpid}}'>Sửa</a>|<a href='cpid/delete?cpid={{$row->cpid}}'onclick='return confirmSubmit()'>Xóa</a></td>
                        </tr>
            		<li>cpid : {{$row->cpid}} | Ðối tác : {{$row->partner_name}}</li>
            	@endforeach -->
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
                        <div class="pull-right" style="margin-top: -20px;">
                            {!! $results->appends(['game1' => $game1] )->render() !!}
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
                        url: '{{url()}}/cpid1',
                        data: {data_ids:ids_string},
                        success: function(result) {
//                            alert("a");
                            location.reload();
                            //dataTable.draw(); // redrawing datatable
                        },
                        async:false
                    });
                }
                return true ;
            }
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