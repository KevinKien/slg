@extends('app')

@section('htmlheader_title')
    Trang cập nhật một server
@endsection
@section('contentheader_title','Cập nhật một server')
@endsection
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/server?id=<?php print$_GET['id'];?>'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group">
                        <label> Hãy chọn Game: </label>
                        <select id='appid' class='form-control' name='appid'>
                            <?php
                            $option1='';
                                foreach ($marchent_app1 as $row1){
                                $option1.="  <option value='".$row1->id.">".$row1->name."</option>  ";
                                }
                             print$option1;
                             $option2='';
                                foreach ($marchent_app2 as $row2){
                                $option2.="  <option value='".$row2->id.">".$row2->name."</option>  ";
                                } 
                             print$option2;
                            ?>
                        </select>
                    </div>
                    <div class="form-group @if ($errors->has('serverid')) has-error @endif">
                        <label>Nhập id</label>
                        <input id='serverid' class='form-control' type='text'name='serverid'value="<?php
                                   foreach ($results as $value){
                                       
                                   }
                                   print$value->serverid;
                        ?>" >
                        @if ( $errors->has('serverid') )<p class="help-block" style="color: red;">{{ $errors->first('serverid') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('servername')) has-error @endif">
                        <label>Nhập tên</label>
                        <input id='servername' class='form-control' type='text'name='servername' value="<?php
                        foreach ($results as $value){
                                       
                                   }
                                   print$value->servername;
                        ?>">
                        @if ( $errors->has('servername') )<p class="help-block" style="color: red;">{{ $errors->first('servername') }}</p> @endif
                    </div>
                    <div class="form-group">
                        <label> Hãy chọn trạng thái: </label>
                        <select id='status' class='form-control' name='status'>
                            <?php
                            $out='';
                            foreach ($results as $value){
                                      $option=$value->status; 
                                      if($option==1){
                                          $out.='<option value="1">Hoạt động</option>
                                                    <option value="0">Chưa hoạt động</option>';
                                        }
                                        if($option==0){
                                          $out.='<option value="0">Chưa hoạt động</option><option value="1">Hoạt động</option>
                                                    ';
                                        }
                                   }
                            print $out;
                                   ?>
                            
                        </select>
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