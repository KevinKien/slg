@extends('app')

@section('htmlheader_title')
    Trang thêm mới một server
@endsection
@section('contentheader_title','Thêm một server')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>

        <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/server'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group">
                    <label> Hãy chọn Game: </label>
                    <select id='appid' class='form-control' name='appid'>
                        @foreach($marchent_app as $row2)
                            <option value='{{$row2->id}}'>{{$row2->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label> Hãy chọn Partner: </label>
                    <select id='partner_id' class='form-control' name='partner_id'>
                        <option value='0'>SLG</option>
                        @foreach($parner as $row2)
                            <option value='{{$row2->partner_id}}'>{{$row2->partner_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if ($errors->has('serverid')) has-error @endif">
                    <label>Nhập id *</label>
                    <input id='serverid' class='form-control' type='text'name='serverid' value="{{old('serverid')}}" >
                    <!--@if ( $errors->has('serverid') )<p class="help-block" style="color: red;">{{ $errors->first('serverid') }}</p> @endif  -->
                </div>
                <div class="form-group @if ($errors->has('servername')) has-error @endif">
                    <label>Nhập tên *</label>
                    <input id='servername' class='form-control' type='text'name='servername' value="{{old('servername')}}"  >
                    <!--@if ( $errors->has('servername') )<p class="help-block" style="color: red;">{{ $errors->first('servername') }}</p> @endif -->
                </div>
                <div class="form-group ">
                    <label>Nhập domain server</label>
                    <input id='serverdomain' class='form-control' type='text'name='serverdomain'  >

                </div>
                <div class="form-group">
                    <label> Hãy chọn trạng thái: </label>
                    <select id='status' class='form-control' name='status'>
                        <option value="1">Hoạt động</option>
                        <option value="0">Chưa hoạt động</option>
                    </select>
                </div>
                <div class="form-group">
                    <label> Server mới : </label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios" value="0" checked="">
                            Old
                        </label>
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios" value="1" >
                            New
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