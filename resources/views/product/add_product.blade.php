@extends('app')

@section('htmlheader_title')
    Trang thêm mới một Product
@endsection
@section('contentheader_title','Thêm một product')
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/merchant_app_product'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group">
                        <label> Hãy chọn game: </label>
                        <select id='merchant_app_id' name="merchant_app_id" class='form-control'>
                            <?php
                                $option='';
                                foreach ($marchent_app as $value){
                                    $option.='<option value="'.$value->id.'">'.$value->name.'</option>';
                                }
                                print $option;
                            ?>
                        </select>
                    </div>
                    <div class="form-group @if ($errors->has('product_name')) has-error @endif">
                        <label>Hãy nhập tên</label>
                        <input id='product_name' class='form-control' type='text'name='product_name' value="{{old('product_name')}}" >
                        @if ( $errors->has('product_name') )<p class="help-block" style="color: red;">{{ $errors->first('product_name') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('product_price')) has-error @endif">
                        <label>Hãy nhập Giá</label>
                        <input id='product_price' class='form-control' type='text'name='product_price' value="{{old('product_price')}}" >
                        @if ( $errors->has('product_price') )<p class="help-block" style="color: red;">{{ $errors->first('product_price') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('amount_fpay')) has-error @endif">
                        <label>Hãy nhập tiền quy đổi fpay</label>
                        <input id='amount_fpay' class='form-control' type='text'name='amount_fpay' value="{{old('amount_fpay')}}" >
                        @if ( $errors->has('amount_fpay') )<p class="help-block" style="color: red;">{{ $errors->first('amount_fpay') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('product_description')) has-error @endif">
                        <label>Hãy nhập mô tả</label>
                        <input id='product_description' class='form-control' type='text'name='product_description'value="{{old('product_description')}}" >
                        @if ( $errors->has('product_description') )<p class="help-block" style="color: red;">{{ $errors->first('product_description') }}</p> @endif
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