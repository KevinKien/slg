@extends('app')

@section('htmlheader_title')
    Trang sửa một Đối tác
@endsection
@section('contentheader_title','Cập nhật một đối tác')
@section('main-content')
    <div class="box box-warning">
        <div class="box-header">

        </div>
        <?php
        foreach ($partner as $row){

        }
        ?>
        <form id='form-editpartner' accept-charset='UTF-8' method='post' action='/partner/edit?partnerid=<?php print $_GET['partnerid']?>'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group @if ($errors->has('partner-name')) has-error @endif">
                    <label> Hãy nhập tên Đối tác: </label>
                    <input id='partner-name' class='form-control' type='text'name='partner-name' value='<?php print $row->partner_name;?>'>
                    <!-- @if ( $errors->has('partner-name') )<p class="help-block" style="color: red;">{{ $errors->first('partner-name') }}</p> @endif -->
                </div>
                <div class="form-group @if ($errors->has('payment-url-callback')) has-error @endif">
                    <label>Hãy nhập payment_url_callback</label>
                    <input id='payment-url-callback' class='form-control' type='text'name='payment-url-callback' value='<?php print $row->payment_url_callback; ?>' >
                    <!-- @if ( $errors->has('payment-url-callback') )<p class="help-block" style="color: red;">{{ $errors->first('payment-url-callback') }}</p> @endif -->
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">Cập nhật</button>
                <a  class="btn btn-default" href='/partner/delete?partnerid=<?php print $_GET['partnerid']?>'onclick='return confirmSubmit()'>Xóa</a>
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