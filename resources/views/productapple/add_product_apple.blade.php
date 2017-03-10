@extends('app')

@section('htmlheader_title')
    Trang thêm mới một Product apple
@endsection
@section('contentheader_title','Thêm một product apple')
@section('main-content')

        <div class="box box-warning">
            <div class="box-header">
                
            </div>
            
            <form id='form-addcpid' accept-charset='UTF-8' method='post' action='/merchant_app_product_apple'>
                <div class="box-body">
                    <input name='_token' type='hidden'value='csrf_token()'>
                    <div class="form-group @if ($errors->has('product_id')) has-error @endif">
                        <label>Hãy mã product</label>
                        <input id='product_id' class='form-control' type='text'name='product_id' value="{{old('product_id')}}" >
                        @if ( $errors->has('product_id') )<p class="help-block" style="color: red;">{{ $errors->first('product_id') }}</p> @endif
                    </div>
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
                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                        <label>Hãy nhập tên</label>
                        <input id='title' class='form-control' type='text'name='title' value="{{old('title')}}">
                        @if ( $errors->has('title') )<p class="help-block" style="color: red;">{{ $errors->first('title') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('amount')) has-error @endif">
                        <label>Hãy nhập Tiền</label>
                        <input id='amount' class='form-control' type='text'name='amount' value="{{old('amount')}}" >
                        @if ( $errors->has('amount') )<p class="help-block" style="color: red;">{{ $errors->first('amount') }}</p> @endif
                    </div>
                    
                    <div class="form-group @if ($errors->has('money_in_game')) has-error @endif">
                        <label>Hãy nhập tiền trong game</label>
                        <input id='money_in_game' class='form-control' type='text'name='money_in_game'value="{{old('money_in_game')}}" >
                        @if ( $errors->has('money_in_game') )<p class="help-block" style="color: red;">{{ $errors->first('money_in_game') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        <label>Hãy nhập mô tả</label>
                        <input id='description' class='form-control' type='text'name='description' value="{{old('description')}}" >
                        @if ( $errors->has('description') )<p class="help-block" style="color: red;">{{ $errors->first('description') }}</p> @endif
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