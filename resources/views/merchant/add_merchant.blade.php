
@extends('app')

@section('htmlheader_title')
    Trang thêm mới một Thông tin game
@endsection
@section('contentheader_title','Thêm một một thông tin game')
@section('css-current')
<!-- iCheck -->
    <link href="//id.slg.vn/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap time Picker -->
    <link href="//id.slg.vn/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
    <link href="//id.slg.vn/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
@endsection
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addmerchant' accept-charset='UTF-8' method='post' action='/merchant_app'>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_5" data-toggle="tab" aria-expanded="true">Thông tin cơ bản</a></li>
                                <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="true">Thông tin thêm</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_5">
                       
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <label> Hãy nhập tên cho game: </label>
                                    <input id='name' class='form-control' type='text' name='name' value="{{old('name')}}" >
                                    @if ( $errors->has('name') )<p class="help-block" style="color: red;">{{ $errors->first('name') }}</p> @endif
                                </div>
                                <div class="form-group">
                                    <label> Chọn kiểu game: </label>
                                    <select name="gametype" id="gametype" class='form-control'>
                                        <option value="0">Webgame</option>
                                        <option value="1">Mobilegame</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label> Chọn url_callback: </label>
                                    <select name="clientid" id="clientid" class='form-control'>
                                        <?php
                                        $option='';
                                        foreach ($results as $value){
                                          $option.='<option value="'.$value->client_id.'">'.$value->redirect_uri.'</option>';  
                                        }
                                        print $option;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group @if ($errors->has('slug')) has-error @endif">
                                    <label>Nhập slug</label>
                                    <input id='slug' class='form-control' type='text' name='slug' value="{{old('slug')}}" > 
                                    @if ( $errors->has('slug') )<p class="help-block" style="color: red;">{{ $errors->first('slug') }}</p> @endif
                                </div>
                                <div class="form-group">
                                    <label> image menu : </label>
                                    <input id='image_menu' class='form-control' name='image_menu' value="{{old('image_menu')}}"  >
                                </div>

                                <div class="form-group">
                                    <label> Mức độ ưu tiên : </label>
                                    <input id='order' class='form-control' type='text' name='order' value="{{old('order')}}" >
                                </div>
                                <div class="form-group">
                                    <label>Chọn Trạng thái</label>
                                    <select name="status" id="status" class='form-control'>
                                        <option value="0">Maintain</option>
                                        <option value="1">Public</option>
                                        <option value="2">Not public</option>
                                    </select>
                                </div>
                                 <div class="form-group">
                                    <label>Nhập đường dẫn file ảnh</label>
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">

                                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">45X45(thumb)</a></li>
                                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true">1600*800(slider)</a></li>
                                            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true">312*110(midder)</a></li>
                                            <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="true">312*200(content)</a></li>
                                             <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="true">150*75(logo)</a></li>
                                             <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="true">295*108(profile)</a></li>
                                             <li class=""><a href="#tab_9" data-toggle="tab" aria-expanded="true">590px × 90px (Iframe Slider)</a></li>

                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active @if ($errors->has('thumb')) has-error @endif" id="tab_1"><input id='thumb' class='form-control' name='thumb' value="{{old('thumb')}}" >
                                                @if ( $errors->has('thumb') )<p class="help-block" style="color: red;">{{ $errors->first('thumb') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('slider')) has-error @endif" id="tab_2"><input id='slider' class='form-control' name='slider' value="{{old('slider')}}" >
                                                @if ( $errors->has('slider') )<p class="help-block" style="color: red;">{{ $errors->first('slider') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('midder')) has-error @endif" id="tab_3"><input id='midder' class='form-control' name='midder' value="{{old('midder')}}" >
                                                @if ( $errors->has('midder') )<p class="help-block" style="color: red;">{{ $errors->first('midder') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('content')) has-error @endif" id="tab_4"><input id='content' class='form-control' name='content'value="{{old('content')}}"  >
                                                @if ( $errors->has('content') )<p class="help-block" style="color: red;">{{ $errors->first('content') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('logo')) has-error @endif" id="tab_7"><input id='logo' class='form-control' name='logo' value="{{old('logo')}}" >
                                                @if ( $errors->has('logo') )<p class="help-block" style="color: red;">{{ $errors->first('logo') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('profile')) has-error @endif" id="tab_8"><input id='profile' class='form-control' name='profile' value="{{old('profile')}}" >
                                                @if ( $errors->has('profile') )<p class="help-block" style="color: red;">{{ $errors->first('profile') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('iframe_slider')) has-error @endif" id="tab_9"><input id='iframe_slider' class='form-control' name='iframe_slider' value="{{old('iframe_slider')}}" >
                                                @if ( $errors->has('iframe_slider') )<p class="help-block" style="color: red;">{{ $errors->first('iframe_slider') }}</p> @endif
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                            
                            <div class="form-group">
                                    <label>Nhập một số đường dẫn cần thiết</label>
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">

                                            <li class="active"><a href="#tab_11" data-toggle="tab" aria-expanded="true">thumb</a></li>
                                            <li class=""><a href="#tab_12" data-toggle="tab" aria-expanded="true">slider</a></li>
                                            <li class=""><a href="#tab_13" data-toggle="tab" aria-expanded="true">midder</a></li>
                                            <li class=""><a href="#tab_14" data-toggle="tab" aria-expanded="true">content</a></li>
                                             <li class=""><a href="#tab_15" data-toggle="tab" aria-expanded="true">logo</a></li>
                                             <li class=""><a href="#tab_16" data-toggle="tab" aria-expanded="true">profile</a></li>
                                             
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active @if ($errors->has('thumb_url')) has-error @endif" id="tab_11"><input id='thumb_url' class='form-control' name='thumb_url'value="{{old('thumb_url')}}"  >
                                                @if ( $errors->has('thumb_url') )<p class="help-block" style="color: red;">{{ $errors->first('thumb_url') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('slider_url')) has-error @endif" id="tab_12"><input id='slider_url' class='form-control' name='slider_url' value="{{old('slider_url')}}" >
                                                @if ( $errors->has('slider_url') )<p class="help-block" style="color: red;">{{ $errors->first('slider_url') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('midder_url')) has-error @endif" id="tab_13"><input id='midder_url' class='form-control' name='midder_url' value="{{old('midder_url')}}" >
                                                @if ( $errors->has('midder_url') )<p class="help-block" style="color: red;">{{ $errors->first('midder_url') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('content_url')) has-error @endif" id="tab_14"><input id='content_url' class='form-control' name='content_url'value="{{old('content_url')}}"  >
                                                @if ( $errors->has('content_url') )<p class="help-block" style="color: red;">{{ $errors->first('content_url') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('logo_url')) has-error @endif" id="tab_15"><input id='logo_url' class='form-control' name='logo_url' value="{{old('logo_url')}}" >
                                                @if ( $errors->has('logo') )<p class="help-block" style="color: red;">{{ $errors->first('logo') }}</p> @endif
                                            </div>
                                            <div class="tab-pane @if ($errors->has('profile_url')) has-error @endif" id="tab_16"><input id='profile_url' class='form-control' name='profile_url' value="{{old('profile_url')}}"  >
                                                @if ( $errors->has('profile_url') )<p class="help-block" style="color: red;">{{ $errors->first('profile_url') }}</p> @endif
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                <div class="form-group">
                                    <label> Game hot : </label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadioshot" id="optionsRadioshot" value="0" checked="">
                                            No
                                        </label>
                                        <label>
                                            <input type="radio" name="optionsRadioshot" id="optionsRadioshot" value="1" >
                                            Hot
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label> Game New : </label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadiosnew" id="optionsRadiosnew" value="0" checked="">
                                            No
                                        </label>
                                        <label>
                                            <input type="radio" name="optionsRadiosnew" id="optionsRadiosnew" value="1" >
                                            New
                                        </label>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab_6">
                                
                                <div class="form-group @if ($errors->has('facebook_id')) has-error @endif">
                                    <label>Nhập facebook_id</label>
                                    <input id='facebook_id' class='form-control' type='text' name='facebook_id'value="{{old('facebook_id')}}" >
                                    @if ( $errors->has('facebook_id') )<p class="help-block" style="color: red;">{{ $errors->first('facebook_id') }}</p> @endif
                                </div>
                                <div class="form-group ">
                                    <label>Nhập facebook_secret</label>
                                    <input id='facebook_secret' class='form-control' type='text' name='facebook_secret' >
                                    @if ( $errors->has('facebook_secret') )<p class="help-block" style="color: red;">{{ $errors->first('facebook_secret') }}</p> @endif
                                </div>
                                <div class="form-group @if ($errors->has('url_homepage')) has-error @endif ">
                                    <label>Nhập url homepage</label>
                                    <input id='url_homepage' class='form-control' type='text' name='url_homepage' value="{{old('url_homepage')}}">
                                    @if ( $errors->has('url_homepage') )<p class="help-block" style="color: red;">{{ $errors->first('url_homepage') }}</p> @endif
                                </div>
                                <div class="form-group @if ($errors->has('url_news')) has-error @endif ">
                                    <label>Nhập link tin tức</label>
                                    <input id='url_news' class='form-control' type='text' name='url_news' value="{{old('url_news')}}" >
                                    @if ( $errors->has('url_news') )<p class="help-block" style="color: red;">{{ $errors->first('url_news') }}</p> @endif
                                </div>
                                <div class="form-group ">
                                    <label>Nhập mô tả</label>
                                    <input id='description' class='form-control' type='text' name='description'  >
                                </div>

                                <div class="form-group ">
                                    <label>Nhập số người dùng</label>
                                    <input id='user_num' class='form-control' type='text' name='user_num'  >
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="topcoin"> Có topcoin được trên web hay không?
                                    </label>
                                </div>
                            </div>
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
        <script>
            function addRow() {
    var div = document.createElement('div');

    div.className = 'row';

    div.innerHTML = '<input type="text" name="slice_90X90[]" value="" id="slice_90X90[]" type="url" class="form-control"/>\
        <input type="button" value="REMOVE" onclick="removeRow(this)">';

     document.getElementById('content').appendChild(div);
}

function removeRow(input) {
    document.getElementById('content').removeChild( input.parentNode );
}
        </script>
        <script>
$("#form-addcpid").validate();
</script>
@endsection
        @section('js-current')
        <script type="text/javascript">

$(function () {
    //Date range picker
    $('#created').datetimepicker({
            format: 'DD-MM-YYYY',
            maxDate: moment()
        });
        });
        
    </script>
    @endsection