@extends('app')

@section('htmlheader_title', 'Tìm kiếm người dùng')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Search User</h3>
                </div><!-- /.box-header -->

                {!! Form::open([
                'route' => 'blocked-payment.search',
                'class' => 'form-horizontal',
                'method' => 'GET'
                ]) !!}

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('keyword', 'UserName *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('keyword', Request::get('keyword'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div><!-- /.box-body -->


                <div class="box-footer">
                    <a href="javascript:history.back();" class="btn btn-default">Back</a>
                    <a href="/blocked-payment/add" class="btn btn-default">Thêm user</a>
                    {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
                </div><!-- /.box-footer -->

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@if(!empty($listusers))
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">User Table</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox"  id="bulkDelete"  /></th>
                                <th>uid</th>
                                <th>tên</th>                            
                                <th>card_telco</th>                            
                                <th>atm_napas</th>                            
                                <th>visa_napas</th>                            
                                <th>visa_nganluong</th>                            
                                <th>chuyển coin</th>            
                                <th>tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listusers as $user)
                            <tr>                 
                                <td><input type='checkbox'  class='deleteRow' value="{{$user->uid }}" /> </td>
                                <td>{{ $user->uid }}</td>
                                <td>{{ $user->username }}</td>
                                <td>@if($user->card_telco == '0')
                                        mở
                                    @else
                                        chặn
                                    @endif   
                                </td>
                                <td>@if($user->atm_napas == '0')
                                        mở
                                    @else
                                        chặn
                                    @endif   
                                </td>
                                <td>@if($user->visa_napas == '0')
                                        mở
                                    @else
                                        chặn
                                    @endif</td>
                                <td>@if($user->visa_nganluong == '0')
                                        mở
                                    @else
                                        chặn
                                    @endif</td>
                                <td>@if($user->coin_transfer == '0')
                                        mở
                                    @else
                                        chặn
                                    @endif</td>
                                <td><a href="{{ route('blocked-payment.edit', $user->uid) }}"><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i>Edit</button></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="btn-group">
                                <button  class="btn btn-default btn-sm" onclick='return confirmSubmit()'><i class="fa fa-trash-o"></i></button>
                            </div><!-- /.btn-group -->
                            <button id="refresh" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>                            
                        </div>
                    </div>
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
                        url: '{{url()}}/blocked-payment/delete',
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
@endif
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
