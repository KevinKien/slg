@extends('app')

@section('htmlheader_title')
    Trang thêm gift code item
@endsection
@section('contentheader_title','Thêm code item vòng quay')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>
        <form id='form-additem' accept-charset='UTF-8' method='post' action='/wheel/addgiftcode?id=<?php print$_GET['id'];?>'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group">
                    <table id="itemtable" class='table table-hover table-striped'>
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Số lượng item</th>
                            <th>GiftCode</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $out='';
                        $i = 0;
                        foreach($list_item as $key => $row){
                            $i++;
                            foreach($list_quantity as $key1 => $row1){
                                if($key == $key1){
                            $item =  explode("_", $key);
                            $out.="
                            <tr>
                            <td><input readonly class='form-control' name='item".$i."' id='item".$i."'  value='".$item[0]."'> </td>
                            <td><input readonly class='form-control' name='quantity".$i."' id='quantity".$i."' value='".$row1."'> </td>
                            <td><textarea name='giftcode".$i."'  rows='2' cols='50'></textarea> </td>
                            </tr>
                                ";
                                }
                            }
                        }
                        print $out;
                        ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">Add</button>
                <a href='javascript:goback()'>Cancel</a>
            </div>
        </form>

    </div>
    <script>
        function goback() {
            history.back(-1)
        }</script>

@endsection
@section('js-current')
    <script type="text/javascript">

    </script>
@endsection