@extends('app')
@section('htmlheader_title')
    Trang thêm mới Event
@endsection
@section('contentheader_title','Thêm Event')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<link href="{!! asset('plugins/daterangepicker/daterangepicker-bs3.css') !!}" rel="stylesheet" type="text/css" />
<!-- Bootstrap time Picker -->
<link href="{!! asset('plugins/timepicker/bootstrap-timepicker.min.css') !!}" rel="stylesheet"/>
<link href="{!! asset('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet"/>
@section('main-content')
    <style>
        .content-wrapper{
            min-height:668px!important;
        }
        .checkboxThree {  width: 80px;  height: 24px;  background: #ccc;  border-radius: 50px;  position: relative;
        }
        .checkboxThree:before {  content: 'ON';  position: absolute;  top: 1px;  left: 5px;  height: 2px;  color: DARKSEAGREEN;  font-size: 15px;
        }
        .checkboxThree:after {  content: 'OFF';  position: absolute;  top: 1px;  left: 50px;  height: 2px;  color: #000000;font-size: 14px;opacity: 0.3;
        }
        .checkboxThree label {  display: block; width:  35px;  height: 20px;  border-radius: 50px;  transition: all .5s ease;  cursor: pointer;  position: absolute;  top: 2px;  z-index: 1;  left: 1px;  background: #ddd;
        }
        .checkboxThree input[type=checkbox]:checked + label {  left: 44px;  background: DARKSEAGREEN;
        }
        label a {
            border: 1px solid darkgreen;  background: DARKSEAGREEN;  color: #fff;  font-size: 12px;
        }
        .display{display: none;}
        .display2{display: block}
        .note{
            font-weight: 700;opacity: 0.7;color: #F39C12;
        }
    </style>

    <div class="container-build">
        <div class="row">
            <div class="col-md-12">
            <div class="box box-warning">
                <form id='form-additem' accept-charset='UTF-8' method='post' action='{!! route('post_event') !!}' enctype='multipart/form-data'>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <input name='_token' type='hidden' value='csrf_token()'>
                                <div class="form-group">
                                    <label> Tên Event  </label>
                                    <input class='form-control' type='text' name='eventname' placeholder="Tên Event" value="{{old('eventname')}}" >
                                </div>
                                <div class="form-group ">
                                    <label> Description  </label>
                                    <input class="form-control" name="description" placeholder="Tiêu đề Event" type='text' value="{{old('description')}}" >
                                </div>
                                <div class="form-group ">
                                    <label> Time Min </label>
                                    <div class='input-group date' id='datetimepicker6'>
                                        <input type='text' class="form-control" placeholder="Thời gian bắt đầu"  name="timeMin" value="{!! old('timeMin') !!}"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label> Time Max  </label>
                                    <div class='input-group date' id='datetimepicker7'>
                                        <input type='text' class="form-control" placeholder="Thời gian kết thúc" name="timeMax" value="{!! old('timeMax') !!}" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div><label> Ảnh Preview </label></div>
                                    <p id="img_preview">
                                    </p>
                                    <label for="file_img_preview">
                                        <a class="btn info">Chèn ảnh</a><span class="note" style="margin-left: 10px">Kích thước 250x200</span>
                                    </label>
                                    <input type="file" style="display:none" class="form-control" name="image"  id="file_img_preview">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Loại Event</label>
                                    <select id="itemnumber" name="choose_event" class="form-control">
                                        <option value="0">Chọn Loại Event</option>
                                        <option value="1">— 1 Code dành cho tất cả người dùng</option>
                                        <option value="2">— 1 Code 1 người dùng</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Games</label>
                                    <select id="itemnumber2" name="choose_game" class="form-control">
                                        <option value="0">Chọn Game</option>
                                        @foreach($games as $item)
                                            <option value="{!! $item->id !!}">— {!! $item->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="multiple">Danh Sách Server</label>
                                    <p class="note">Note : Chọn tất cả thì thôi chọn riêng server . Chọn riêng server thì thôi không chọn tất cả</p>
                                    <select id="multiple2" class="form-control select2-multiple"  name="choose_sever[]" multiple >

                                    </select>
                                </div>
                                <div class="form-group display" id="d_num">
                                    <label> Number  </label>
                                    <input class="form-control" name="number" step="10" type='number' placeholder="Số lượng " min="0" max="10000"  value="{{old('number')}}" >
                                </div>
                                <div class="form-group display" id="d_gift">
                                    <label> Gift Code  </label>
                                    <input class="form-control" name="gift_code_text" placeholder="Nhập Gift Code" type='text' value="{{old('gift-code')}}" >
                                </div>
                                <div class="form-group display" id="d_file_gift">
                                    <div><label> Gift Code </label></div>
                                    <label for="file_txt_preview">
                                        <a class="btn info">Chèn file</a> <span style="margin-left: 20px;" id="txt_preview"></span>
                                    </label>
                                    <input type="file" style="display:none" class="form-control" name="file_txt"  id="file_txt_preview">
                                </div>
                                <div class="form-group">
                                    <label> Link Share FaceBook </label>
                                    <p class="note">Nếu k cho link thì k có điều kiện share</p>
                                    <input class='form-control' type='text' name='share' placeholder="Link share" value="{{old('share')}}" >
                                </div>
                                <div class="form-group">
                                    <label> Link ảnh thông tin về code  </label>
                                    <input class='form-control' type='text' name='image_thongtin' placeholder="Ảnh thông tin gift code" value="{{old('image_thongtin')}}" >
                                </div>
                                <div class="form-group">
                                    <label> Status </label>
                                    <div class="checkboxThree">
                                        <input type="checkbox" value="1" id="checkboxThreeInput" name="checkbox"
                                               style="display: none"/>
                                        <label for="checkboxThreeInput"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Thêm</button>
                            <a href='javascript:goback()' class="btn btn-defalt" style="border:1px solid #ccc">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
            </div>
        </div>
    </div>
@endsection
@section('js-current')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).on('change','#itemnumber',function (e) {
            e.preventDefault();
            var value = $(this).val();
            switch(value) {
                case "1":
                    $('#d_gift').addClass("display2");
                    $('#d_file_gift').removeClass("display2");
                    break;
                case "2":
                    $('#d_file_gift').addClass("display2");
                    $('#d_gift').removeClass("display2");
                    break;
                default:
                    $('#d_file_gift').removeClass("display2");
                    $('#d_gift').removeClass("display2");
            }
        });
        $('#multiple2').select2({
            allowClear: true,
            placeholder: "Không có games nào được chọn",
            minimumResultsForSearch: -1,
        });
        //

        $(document).on('change','#itemnumber2',function (e) {
            e.preventDefault();
            id_games = $(this).val();
            if(id_games == 0 ){
                $('#multiple2').html("");
            }else{
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type:"post",
                    url:"{{route('ajax_gift_game')}}",
                    data:{'id_games':id_games},
                    success:function(data){
                        if(data.status == true){
                            $('#multiple2').html(data.html);
                        }else{
                            alert("Có lỗi xảy ra");
                        }
                    },
                    cache:false,
                    dataType: 'json'
                });
            }
        });
        //file image
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var content = '<img src="'+e.target.result+'">';
                    $('#img_preview').html(content);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).on('change','#file_img_preview',function () {
            readURL(this);
        });
        //file txt
        function readURL2(input) {
            if (input.files && input.files[0]) {
                filename = $('#file_txt_preview').val().split('\\').pop();
                var reader = new FileReader();
                reader.onload = function (e) {

                    $('#txt_preview').text(filename);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).on('change','#file_txt_preview',function () {
            readURL2(this);
        });
$(function () {
    $('#datetimepicker6').datetimepicker();
    $('#datetimepicker7').datetimepicker({
        useCurrent: false //Important! See issue #1075
    });
    $("#datetimepicker6").on("dp.change", function (e) {
        e.preventDefault();
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function (e) {
        e.preventDefault();
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
});
    </script>
    <script>
        function goback() {
            history.back(-1)
        }</script>

@endsection