@extends('app')

@section('htmlheader_title')
    Trang thêm mới một user
@endsection
@section('contentheader_title','Thêm một user')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>

        <form id='form-adduser' accept-charset='UTF-8' method='post' action='/blocked-payment/add'>
            <div class="box-body">                
                <div class="form-group">
                    <label> nhập username: </label>
                    <input id='username' class='form-control' type='text'name='username'  >
                </div>                                                              
                <div class="form-group">
                    <label> Hãy chọn loại block: </label>
                    <div class="blockall">                        
                        <label>                            
                            <input type="checkbox" name="blockall" id="blockall" >Chặn tất cả nạp tiền
                        </label>
                    </div>
                    <div class="blockall">                        
                        <label>  
                            <input type="checkbox" name="block_telco" id="block_telco" class="block_telco" value="1"> Chặn nạp thẻ viễn thông
                        </label>
                    </div>
                    <div class="blockall">
                        <label>
                            <input type="checkbox" name="block_atm_napas" id="block_atm_napas" class="block_telco" value="1"> Chặn nạp ATM napas
                        </label>
                    </div>
                    <div class="blockall">
                        <label>
                            <input type="checkbox" name="block_visa_napas" id="block_visa_napas" class="block_telco" value="1"> Chặn nạp visa napas
                        </label>
                    </div>        
                    <div class="blockall">
                        <label>
                            <input type="checkbox" name="block_visa_nganluong" id="block_visa_nganluong" class="block_telco" value="1"> Chặn nạp visa ngân lượng
                        </label>   
                    </div>                                                             
                </div>
            </div>
                
                <div class="form-group">
                    <label> Chặn chuyển coin : </label>
                    <div class="transfer">
                        <label>
                            <input type="radio" name="optionstransfer" id="optionsRadios" value="0" checked="">
                            Mở
                        </label>
                        <label>
                            <input type="radio" name="optionstransfer" id="optionsRadios" value="1" >
                            Chặn
                        </label>
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
    
    
@endsection
@section('js-current')
   <script type="text/javascript">
        $(document).ready(function() {

            $("#blockall").on('click',function() { // bulk checked
                var status = this.checked;
//                alert("a");
                $(".block_telco").each( function() {
                    $(this).prop("checked",status);
                });
            });            
        } );


    </script>
@endsection