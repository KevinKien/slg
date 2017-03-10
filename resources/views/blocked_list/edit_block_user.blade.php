@extends('app')

@section('htmlheader_title')
    Trang sửa một user
@endsection
@section('contentheader_title','Sửa một user')
@section('main-content')

    <div class="box box-warning">
        <div class="box-header">

        </div>

        <form id='form-adduser' accept-charset='UTF-8' method='post' action='/blocked-payment/update'>
            <div class="box-body">                
                <div class="form-group">
                    <label> nhập username: </label>
                    <input id='username' class='form-control' type='text'name='username'  value="{{$listusers[0]->username}}" disabled>
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
                            @if($listusers[0]->card_telco == '1')
                                <input type="checkbox" name="block_telco" id="block_telco" class="block_telco" value="1" checked=""> Chặn nạp thẻ viễn thông
                            @else
                                <input type="checkbox" name="block_telco" id="block_telco" class="block_telco" value="1"> Chặn nạp thẻ viễn thông
                            @endif                            
                        </label>
                    </div>
                    <div class="blockall">
                        <label>
                            @if($listusers[0]->atm_napas == '1')
                                <input type="checkbox" name="block_atm_napas" id="block_atm_napas" class="block_telco" value="1" checked=""> Chặn nạp ATM napas
                            @else
                                <input type="checkbox" name="block_atm_napas" id="block_atm_napas" class="block_telco" value="1"> Chặn nạp ATM napas
                            @endif                             
                        </label>
                    </div>
                    <div class="blockall">
                        <label>
                            @if($listusers[0]->visa_napas == '1')
                                <input type="checkbox" name="block_visa_napas" id="block_visa_napas" class="block_telco" value="1" checked=""> Chặn nạp visa napas
                            @else
                                <input type="checkbox" name="block_visa_napas" id="block_visa_napas" class="block_telco" value="1"> Chặn nạp visa napas
                            @endif 
                            
                        </label>
                    </div>        
                    <div class="blockall">
                        <label>
                            @if($listusers[0]->visa_nganluong == '1')
                                <input type="checkbox" name="block_visa_nganluong" id="block_visa_nganluong" class="block_telco" value="1" checked=""> Chặn nạp visa ngân lượng
                            @else
                                <input type="checkbox" name="block_visa_nganluong" id="block_visa_nganluong" class="block_telco" value="1"> Chặn nạp visa ngân lượng
                            @endif 
                            
                        </label>   
                    </div>                                                             
                </div>
            </div>
                
                <div class="form-group">
                    @if($listusers[0]->coin_transfer == '1')
                        <label> Chặn chuyển coin : </label>
                        <div class="transfer">
                            <label>
                                <input type="radio" name="optionstransfer" id="optionsRadios" value="0" >
                                Mở
                            </label>
                            <label>
                                <input type="radio" name="optionstransfer" id="optionsRadios" value="1" checked="">
                                Chặn
                            </label>
                        </div>
                    @else
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
                    @endif 
                    

                </div>

            </div>
            <div class="box-footer">
                <button class="btn btn-primary" type="submit">Update</button>
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