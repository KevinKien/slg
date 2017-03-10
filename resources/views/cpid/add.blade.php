@extends('app')

@section('htmlheader_title')
    Trang thêm mới một cpid
@endsection
@section('contentheader_title','Thêm một cpid')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>

        <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/cpid'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group">
                    <label> Hãy chọn Đối tác: </label>
                    <select id='add-partner' class='form-control' name='add-partner'>
                        @foreach($parner as $row1)
                            <option value='{{$row1->partner_id}}'>{{$row1->partner_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label> Hãy chọn Game: </label></br>
                    <select id='add-appid' class='form-control' name='add-appid'>
                        @foreach($marchent_app as $row2)
                            <option value='{{$row2->id}}'>{{$row2->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if ($errors->has('cpi_name')) has-error @endif">
                    <label>Nhập tên cpid *</label>
                    <input id='cpi_name' class='form-control' type='text' name='cpi_name'>                    
                </div>
                <div class="form-group @if ($errors->has('google_code')) has-error @endif">
                    <label>Nhập mã nhúng google </label>
                    <input id='google_code' class='form-control' type='text' name='google_code'>
                    
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
                        <label>
                            <input type="radio" name="Show" id="Show" value="0" checked="">
                            No
                        </label>
                        <label>
                            <input type="radio" name="Show" id="Show" value="1" >
                            Yes
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label> Check Revenue : </label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="CheckRevenue" id="CheckRevenue" value="0" checked="">
                            No
                        </label>
                        <label>
                            <input type="radio" name="CheckRevenue" id="CheckRevenue" value="1" >
                            Yes
                        </label>

                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">Thêm</button>
                <a href='javascript:goback()'>Cancel</a>
            </div>
        </form>

    </div>
    <script>
        function goback() {
            history.back(-1)
        }</script>
@endsection