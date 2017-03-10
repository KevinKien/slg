@extends('app')

@section('htmlheader_title')
    Trang liệt kê danh sách sự kiện
@endsection
@section('contentheader_title')
    Danh sách sự kiện
    <div class="box-tools pull-right col-md-3">
        <a class="btn btn-primary btn-block margin-bottom" href="{!! route('add.Gift') !!}">THÊM</a>
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
                                <th></th>
                                <th>STT</th>
                                <th>Image</th>
                                <th>Name event</th>
                                <th>Game</th>
                                <th>Code type</th>
                                <th>Start</th>
                                <th>Stop</th>
                                <th>Status</th>
                                <th>Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $STT = 0;?>
                            @foreach($event as $item)
                                <?php $STT++;
                                    $game = App\Models\GiftCodeServer::select('gift_game_servers.*','merchant_app.*')
                                        ->rightjoin('merchant_app','merchant_app.id','=','gift_game_servers.game_id')
                                        ->where('gift_game_servers.event_id',$item->id)
                                        ->where('gift_game_servers.gift_code_type',$item->giftcode_type)->first();
                                    ?>
                                <tr>
                                    <td><input type='checkbox'  class='deleteRow' value='{!! $item->id !!}' /></td>
                                    <td>{!! $STT !!}</td>
                                    <td><img src="{!! $item->image !!}" alt="" width="40px" height=""></td>
                                    <td>{!! $item->name !!}</td>
                                    <td>{!! $game->name !!}</td>
                                    <td>
                                        @if($item->giftcode_type == 1) Code tân thủ @endif
                                        @if($item->giftcode_type == 2) Code User @endif
                                        @if($item->giftcode_type == 3) Code Server @endif
                                    </td>
                                    <td>{!! date("Y-m-d H:i",strtotime($item->time_min)) !!}</td>
                                    <td>{!! date("Y-m-d H:i",strtotime($item->time_max)) !!}</td>
                                    <td> @if($item->status == 1 ) Công khai @else Ẩn @endif </td>
                                    <td></td>
                                </tr>
                            @endforeach
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
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type:"post",
                        url:"{{route('del_list')}}",
                        data:{'id_events':ids_string},
                        success:function(data){
                            if(data == true){
                                location.reload();
                            }else{
                                alert("chưa có mục nào được chọn");
                            }
                        },
                        cache:false,
                        dataType: 'json'
                    });
                }
                return true ;}
            else{
                return false ;}
        }
    </script>
@endsection
@section('js-current')

@endsection