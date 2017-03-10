@extends('app')

@section('htmlheader_title')
    Trang cập nhật một Product
@endsection
@section('contentheader_title','Cập nhật product')
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/merchant_app_product/edit?productid=<?php print $_GET['productid'];?>'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group">
                        <label> Hãy chọn game: </label>
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
                    <div class="form-group @if ($errors->has('product_name')) has-error @endif">
                        <label>Hãy nhập tên</label>
                        <input id='product_name' class='form-control' type='text'name='product_name' value="<?php print $value->product_name;?>{{old('product_name')}}" >
                        @if ( $errors->has('product_name') )<p class="help-block" style="color: red;">{{ $errors->first('product_name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('product_price')) has-error @endif">
                        <label>Hãy nhập Giá</label>
                        <input id='product_price' class='form-control' type='text'name='product_price' value="<?php print $value->product_price;?>{{old('product_price')}}">
                        @if ( $errors->has('product_price') )<p class="help-block" style="color: red;">{{ $errors->first('product_price') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('amount_fpay')) has-error @endif">
                        <label>Hãy nhập tiền quy đổi fpay</label>
                        <input id='amount_fpay' class='form-control' type='text'name='amount_fpay' value="<?php print $value->amount_fpay;?>{{old('amount_fpay')}}">
                        @if ( $errors->has('amount_fpay') )<p class="help-block" style="color: red;">{{ $errors->first('amount_fpay') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('product_description')) has-error @endif">
                        <label>Hãy nhập mô tả</label>
                        <input id='product_description' class='form-control' type='text'name='product_description' value="<?php print$value->product_description;?>{{old('product_description')}}">
                        @if ( $errors->has('product_description') )<p class="help-block" style="color: red;">{{ $errors->first('product_description') }}</p> @endif
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                    <a href='/merchant_app_product/delete?productid=<?php print $_GET['productid'];?>'onclick='return confirmSubmit()'>Xóa</a>
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