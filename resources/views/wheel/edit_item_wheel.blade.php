@extends('app')

@section('htmlheader_title')
    Trang cập nhập item
@endsection
@section('contentheader_title','Thêm item vòng quay')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>
        <form id='form-additem' accept-charset='UTF-8' method='post' action='/wheel/edit?id=<?php print$_GET['id'];?>'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group @if ($errors->has('eventname')) has-error @endif">
                    <label> Tên sự kiện : </label>
                    <input id='eventname' class='form-control' type='text' name='eventname' value="<?php
                    foreach ($results as $value){

                    }
                    print$value->event;
                    ?>" >
                </div>
                <div class="form-group @if ($errors->has('imageitem')) has-error @endif">
                    <label> Link image item : </label>
                    <input class="form-control" name="imageitem" id="imageitem" type='text' value="<?php
                    foreach ($results as $value){

                    }
                    print$value->image_item;
                    ?>" >
                </div>
                <div class="form-group ">
                    <label>Số lượng item</label>
                    <select id="itemnumber" name="itemnumber">
                        <?php
                        $listoption = ['option1'=>8,'option2'=>9,'option3'=>10];
                        $option = '';
                        foreach ($results as $value){
                        foreach($listoption as $key => $row){
                            if($row == $value->item_number){
                                $option.="<option value='$row' style='font-size:small' selected='selected'>".$row."</option>";
                            }else{
                                $option.="<option style='font-size:small' value='$row'>".$row."</option>";}
                        }
                        print $option;
                        }?>
                    </select>
                </div>
                <div class="form-group @if ($errors->has('turndial')) has-error @endif">
                    <label> Số lượt quay : </label>
                    <input class="form-control" name="turndial" id="turndial" type='text' value="<?php
                    foreach ($results as $value){

                    }
                    print$value->turn_dial;
                    ?>">
                </div>
                <div class="form-group">
                    <label> Sử dụng : </label>
                    <div class="radio">
                        <?php
                        $out='';
                        foreach ($results as $value){
                            $option=$value->is_use;
                            if($option==0){
                                $out.='<label>
                                         <input type="radio" name="optionsRadios" id="optionsRadios" value="0" checked="">
                                         No
                                         </label>
                                         <label>
                                         <input type="radio" name="optionsRadios" id="optionsRadios" value="1" >
                                         Yes
                                        </label>';
                            }
                            elseif($option==1){
                                $out.='<label>
                                         <input type="radio" name="optionsRadios" id="optionsRadios" value="0" >
                                         No
                                         </label>
                                         <label>
                                         <input type="radio" name="optionsRadios" id="optionsRadios" value="1" checked="">
                                         Yes
                                        </label>';
                            }
                        }
                        print $out;
                        ?>
                    </div>

                </div>

                <div class="form-group">
                    <table id="itemtable" class='table table-hover table-striped'>
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Tỷ lệ</th>
                            <th>Số lượng item</th>
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
                            <td><input class='form-control' name='item".$i."' id='item".$i."' placeholder='item ".$i."' value='".$item[0]."'> </td>
                            <td><input class='form-control' name='rate".$i."' id='rate".$i."' placeholder='rate' value='".$row."'> </td>
                            <td><input class='form-control' name='quantity".$i."' id='quantity".$i."' placeholder='quantity' value='".$row1."'> </td>
                                </tr>
                                ";
                                }
                            }
                        }
                        print $out;
                        ?>

                        <?php
                        if(count($list_item)==8){
                        ?>
                        <tr id="row9" hidden="Yes">
                            <td><input class="form-control" name="item9" id="item9" placeholder="item 9"> </td>
                            <td><input class="form-control" name="rate9" id="rate9" placeholder="rate"> </td>
                            <td><input class="form-control" name="quantity9" id="quantity9" placeholder="quantity"> </td>
                        </tr>
                        <tr id="row10" hidden="Yes">
                            <td><input class="form-control" name="item10" id="item10" placeholder="item 10"> </td>
                            <td><input class="form-control" name="rate10" id="rate10" placeholder="rate"> </td>
                            <td><input class="form-control" name="quantity10" id="quantity10" placeholder="quantity"> </td>
                        </tr>
                        <?php
                        }else{
                            ?>
                        <tr id="row10" hidden="Yes">
                            <td><input class="form-control" name="item10" id="item10" placeholder="item 10"> </td>
                            <td><input class="form-control" name="rate10" id="rate10" placeholder="rate"> </td>
                            <td><input class="form-control" name="quantity10" id="quantity10" placeholder="quantity"> </td>
                        </tr>
                         <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">UPDATE</button>
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
        $("#itemnumber").change(function () {
            var app_id = $('#itemnumber').val();
            if(app_id == 8){
                $('#row9').hide();
                $('#row10').hide();
            }else{
                if(app_id == 9){
                    $('#row9').show();
                    $('#row10').hide();
                }else{
                    $('#row10').show();
                }
            }
        });
    </script>
@endsection