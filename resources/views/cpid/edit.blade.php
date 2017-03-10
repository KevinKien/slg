@extends('app')

@section('htmlheader_title')
    Trang cập nhật một cpid
@endsection
@section('contentheader_title','Cập nhật một cpid')

@section('main-content')


    <div class="box box-warning">
        <div class="box-header">

        </div>

        <form id='form-editcpid' accept-charset='UTF-8' method='post' action='/cpid/edit?cpid=<?php print $_GET['cpid']?>'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group">
                    <label> Hãy chọn Đối tác: </label>
                    <select id='add-partner' class='form-control' name='add-partner'>
                        @foreach($results as $row)
                            <option value='{{$row->partner_id}}'>{{$row->partner_name}}</option>
                        @endforeach
                        @foreach($parner as $row1)
                            <option value='{{$row1->partner_id}}'>{{$row1->partner_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label> Hãy chọn Game: </label>
                    <select id='add-appid' class='form-control' name='add-appid'>
                        @foreach($results as $row)
                            <option value='{{$row->id}}'>{{$row->name}}</option>
                        @endforeach
                        @foreach($marchent_app as $row2)
                            <option value='{{$row2->id}}'>{{$row2->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nhập tên cpid *</label>
                    <input id='cpi_name' class='form-control' type='text'name='cpi_name' value="{{$row->cp_name}}" >
                </div>
                <div class="form-group">
                    <label>Nhập mã nhúng google *</label>
                    <input id='ga-id' class='form-control' type='text'name='ga-id' value='{{$row->ga_id}}{{old('ga-id')}}'>
                </div>
                <div class="form-group">
                    <?php $os_id = json_decode(OS_ID);?>
                    <label> Hãy chọn os_id: </label>
                    <select id='add-osid' class='form-control' name='add-osid'>
                        @foreach($os_id as $key => $row)
                            <option value='{{$key}}'> {{$row}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label> Show : </label>
                    <div class="radio">
                        <?php
                        $out='';                        
                        $option=$results[0]->show;
                        if($option==0){
                            $out.='<label>
                            <input type="radio" name="Show" id="Show" value="0" checked="">
                                No
                             </label>
                            <label>
                            <input type="radio" name="Show" id="Show" value="1" >
                                Show
                            </label>';
                        }
                        elseif($option==1){
                            $out.='<label>
                            <input type="radio" name="Show" id="Show" value="0" >
                                No
                             </label>
                             <label>
                             <input type="radio" name="Show" id="Show" value="1" checked="">
                                Show
                            </label>';
                        }                        
                        print $out;
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label> Check Revenue : </label>
                    <div class="radio">
                        <?php
                        $out='';                        
                        $option1=$results[0]->check_revenue;
                        if($option1==0){
                            $out.='<label>
                            <input type="radio" name="CheckRevenue" id="CheckRevenue" value="0" checked="">
                                No
                             </label>
                            <label>
                            <input type="radio" name="CheckRevenue" id="CheckRevenue" value="1" >
                                Yes
                            </label>';
                        }
                        elseif($option1==1){
                            $out.='<label>
                            <input type="radio" name="CheckRevenue" id="CheckRevenue" value="0" >
                                No
                            </label>
                            <label>
                            <input type="radio" name="CheckRevenue" id="CheckRevenue" value="1" checked="">
                                Yes
                            </label>';
                        }                        
                        print $out;
                        ?>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">Cập nhật</button>

                <a class="btn btn-default" href='/cpid/delete?cpid=<?php print $_GET['cpid']?>'onclick='return confirmSubmit()'>Xóa</a>
            </div>
        </form>
    </div>
    <script LANGUAGE="JavaScript">
        function confirmSubmit()
        {
            var agree=confirm("Bạn có muốn xóa mục này không? Nếu xóa thì dữ liệu xẽ bị mất");
            if (agree)
                return true ;
            else
                return false ;
        }
    </script>

@endsection