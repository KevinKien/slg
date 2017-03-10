// check nhận code
$(document).on('click','#d_sm',function (e) {
    e.preventDefault();
    var id_server = $('#checked option:selected').val();
    var event = $('#event').val();
    var game = $('#game').val();
    var code_type = $('#code_type').val();
    if(id_server != 0){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type:"post",
            url:'/ring-gift-code',
            data:{'event':event,'game':game,'server_id':id_server,'code_type':code_type},
            success:function(data){
                if(data.status == true){
                    switch (data.suces){
                        case 0:
                            $('#model1').modal('show');
                            $('#error').html(data.message).css({'font-weight':'700',"font-size":"15px",'text-align':'center'});
                            $('#gift').html(data.message).css({'font-weight':'700',"font-size":"15px"});
                            break;
                        case 1:
                            $('#model1').modal('show');
                            $('#error').html(data.message).css({'font-weight':'700',"font-size":"15px",'text-align':'center'});
                            $('#gift').html(data.message).css({'font-weight':'700',"font-size":"15px"});
                            break;
                        default: window.location.reload();

                    }
                }
                if(data.status == false){
                    switch (data.error){
                        case 0:
                            window.location.reload();
                        break;
                        case 1:
                            $('#model1').modal('show');
                            $('#error').text(data.message);
                            break;
                        case 2:
                            $('#model1').modal('show');
                            $('#error').text(data.message);
                            break;
                        case 3:
                            $('#model1').modal('show');
                            $('#error').text(data.message);
                            break;
                        default: window.location.reload();
                    }
                }
            },
            cache:false,
            dataType:'json'
        });
    }else{
       $('#model1').modal('show');
    }
});

//history
$(document).on('click','#history_code',function (e) {
    e.preventDefault();
    $('#model2').modal('show');
});

// thay đổi server -> số code
$(document).on('change','#checked',function (e) {
    e.preventDefault();
    var server = $(this).val();
    var event = $('#event').val();
    var game = $('#game').val();
    var code_type = $('#code_type').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        type:"post",
        url:"/change-server",
        data:{'event':event,'game':game,'server_id':server,'code_type':code_type},
        success:function(data){
            if(data.status == true){
                $('#total').text(data.count+" Code");
            }
            if(data.status == false){
                window.location.reload();
            }
        },
        cache:false,
        dataType: 'json'
    });
});
