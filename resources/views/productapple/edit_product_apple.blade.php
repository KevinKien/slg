@extends('app')

@section('htmlheader_title')
    Trang cập nhật một Product apple
@endsection
@section('contentheader_title','Cập nhật product apple')
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/merchant_app_product_apple/edit?id=<?php print $_GET['id'];?>'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group @if ($errors->has('product_id')) has-error @endif">
                        <label>Mã product</label>
                        <input id='product_id' class='form-control' type='text'name='product_id' value="<?php
                                foreach ($result as $value){
                                }
                                print $value->product_id;
                            ?>{{old('product_id')}}">
                        @if ( $errors->has('product_id') )<p class="help-block" style="color: red;">{{ $errors->first('product_id') }}</p> @endif
                    </div>
                    <div class="form-group">
                        <label> Chọn game: </label>
                        <select id='merchant_app_id' name="merchant_app_id" class='form-control'>
                            <?php
                                $option='';
                                foreach ($result as $value){
                                    $option.='<option value="'.$value->id.'">'.$value->name.'</option>';
                                }
                                $option1='';
                                foreach ($merchant_app as $value1){
                                    $option1.='<option value="'.$value1->id.'">'.$value1->name.'</option>';
                                }
                                print $option.$option1;
                            ?>
                        </select>
                    </div>
                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                        <label>Tên</label>
                        <input id='title' class='form-control' type='text'name='title' value="<?php print$value->title; ?>{{old('title')}}">
                        @if ( $errors->has('title') )<p class="help-block" style="color: red;">{{ $errors->first('title') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('amount')) has-error @endif">
                        <label>Tiền</label>
                        <input id='amount' class='form-control' type='text'name='amount' value="<?php print$value->amount;?>{{old('amount')}}" >
                        @if ( $errors->has('amount') )<p class="help-block" style="color: red;">{{ $errors->first('amount') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('money_in_game')) has-error @endif">
                        <label>Tiền trong game</label>
                        <input id='money_in_game' class='form-control' type='text'name='money_in_game' value="<?php print$value->money_in_game;?>{{old('money_in_game')}}">
                        @if ( $errors->has('money_in_game') )<p class="help-block" style="color: red;">{{ $errors->first('money_in_game') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        <label>Mô tả</label>
                        <input id='description' class='form-control' type='text'name='description' value="<?php print$value->description;?>{{old('description')}}">
                        @if ( $errors->has('description') )<p class="help-block" style="color: red;">{{ $errors->first('description') }}</p> @endif
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                    <a href='/merchant_app_product_apple/delete?id=<?php print $_GET['id'];?>'onclick='return confirmSubmit()'>Xóa</a>
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