@extends('app')

@section('htmlheader_title')
    Trang thêm mới item
@endsection
@section('contentheader_title','Thêm item vòng quay')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>
        <form id='form-additem' accept-charset='UTF-8' method='post' action='/wheel'>
            <div class="box-body">
                <input name='_token' type='hidden'value='csrf_token()'>
                <div class="form-group @if ($errors->has('eventname')) has-error @endif">
                    <label> Tên sự kiện : </label>
                    <input id='eventname' class='form-control' type='text' name='eventname' value="{{old('eventname')}}" >
                </div>
                <div class="form-group @if ($errors->has('imageitem')) has-error @endif">
                    <label> Link image item : </label>
                    <input class="form-control" name="imageitem" id="imageitem" type='text' value="{{old('imageitem')}}" >
                </div>
                <div class="form-group @if ($errors->has('turndial')) has-error @endif">
                    <label> Số lượt quay : </label>
                    <input class="form-control" name="turndial" id="turndial" type='text' value="{{old('turndial')}}" >
                </div>
                <div class="form-group">
                    <label>Số lượng item</label>
                    <select id="itemnumber" name="itemnumber">
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="form-group">
                    <label> Sử dụng : </label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios" value="0" checked="">
                            No
                        </label>
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios" value="1" >
                            Yes
                        </label>
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
                        <tr>
                            <td><input class="form-control" name="item1" id="item1" placeholder="item 1" value="{{old('item1')}}"> </td>
                            <td><input class="form-control" name="rate1" id="rate1" placeholder="rate" value="{{old('rate1')}}"> </td>
                            <td><input class="form-control" name="quantity1" id="quantity1" placeholder="quantity" value="{{old('quantity1')}}"> </td>
                        </tr>
                        <tr>
                            <td><input class="form-control" name="item2" id="item2" placeholder="item 2" value="{{old('item2')}}"> </td>
                            <td><input class="form-control" name="rate2" id="rate2" placeholder="rate" value="{{old('rate2')}}"> </td>
                            <td><input class="form-control" name="quantity2" id="quantity2" placeholder="quantity" value="{{old('quantity2')}}"> </td>
                        </tr>
                        <tr>
                            <td><input class="form-control" name="item3" id="item3" placeholder="item 3" value="{{old('item3')}}"> </td>
                            <td><input class="form-control" name="rate3" id="rate3" placeholder="rate" value="{{old('rate3')}}"> </td>
                            <td><input class="form-control" name="quantity3" id="quantity3" placeholder="quantity" value="{{old('quantity3')}}"> </td>
                        </tr><tr>
                            <td><input class="form-control" name="item4" id="item4" placeholder="item 4" value="{{old('item4')}}"> </td>
                            <td><input class="form-control" name="rate4" id="rate4" placeholder="rate" value="{{old('rate4')}}"> </td>
                            <td><input class="form-control" name="quantity4" id="quantity4" placeholder="quantity" value="{{old('quantity4')}}"> </td>
                        </tr>
                        <tr>
                            <td><input class="form-control" name="item5" id="item5" placeholder="item 5" value="{{old('item5')}}"> </td>
                            <td><input class="form-control" name="rate5" id="rate5" placeholder="rate" value="{{old('rate5')}}"> </td>
                            <td><input class="form-control" name="quantity5" id="quantity5" placeholder="quantity" value="{{old('quantity5')}}"> </td>
                        </tr><tr>
                            <td><input class="form-control" name="item6" id="item6" placeholder="item 6" value="{{old('item6')}}"> </td>
                            <td><input class="form-control" name="rate6" id="rate6" placeholder="rate" value="{{old('rate6')}}"> </td>
                            <td><input class="form-control" name="quantity6" id="quantity6" placeholder="quantity" value="{{old('quantity6')}}"> </td>
                        </tr><tr>
                            <td><input class="form-control" name="item7" id="item7" placeholder="item 7" value="{{old('item7')}}"> </td>
                            <td><input class="form-control" name="rate7" id="rate7" placeholder="rate" value="{{old('rate7')}}"> </td>
                            <td><input class="form-control" name="quantity7" id="quantity7" placeholder="quantity" value="{{old('quantity7')}}"> </td>
                        </tr>
                        <tr>
                            <td><input class="form-control" name="item8" id="item8" placeholder="item 8" value="{{old('item8')}}"> </td>
                            <td><input class="form-control" name="rate8" id="rate8" placeholder="rate"value="{{old('rate8')}}"> </td>
                            <td><input class="form-control" name="quantity8" id="quantity8" placeholder="quantity" value="{{old('quantity8')}}"> </td>
                        </tr>
                        <tr id="row9" hidden="Yes">
                            <td><input class="form-control" name="item9" id="item9" placeholder="item 9" value="{{old('item9')}}"> </td>
                            <td><input class="form-control" name="rate9" id="rate9" placeholder="rate" value="{{old('rate9')}}"> </td>
                            <td><input class="form-control" name="quantity9" id="quantity9" placeholder="quantity" value="{{old('quantity9')}}"> </td>
                        </tr>
                        <tr id="row10" hidden="Yes">
                            <td><input class="form-control" name="item10" id="item10" placeholder="item 10" value="{{old('item10')}}"> </td>
                            <td><input class="form-control" name="rate10" id="rate10" placeholder="rate" value="{{old('rate10')}}"> </td>
                            <td><input class="form-control" name="quantity10" id="quantity10" placeholder="quantity" value="{{old('quantity10')}}"> </td>
                        </tr>
                    </table>
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