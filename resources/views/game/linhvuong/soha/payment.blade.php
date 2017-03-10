<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>One Piece Online</title>
        <link href="http://app.slg.vn/game/linhvuong_soha/css/style.css" rel="stylesheet" type="text/css" media="all" />
        <script src="http://app.slg.vn/game/linhvuong_soha/css/jquery-1.8.2.min.js" ></script>
        <script src="http://app.slg.vn/tools/js/client.js" ></script>
    </head>

    <body>
        <div class="loadbox">
            <div class="headline"> <a target="_blank" href="http://soap.soha.vn/dialog/topupcash/default/" class="loadsgold"></a> <span class="headtitle"></span> </div>
            <div class="info">

                <form id="myForm" name="frontForm" action="" method="post">
                    <?php if (!empty($data['usersh']->id)): ?>
                        <p>Tên tài khoản: <strong><?php echo $data['usersh']->username; ?></strong></p>
                    <?php else: ?>
                        <p>Tên tài khoản: <strong>Guest</strong></p>
                    <?php endif; ?>

                    <p><span class="sohagold"></span> 
                        <?php
                        if (!empty($data['scoin'])) {
                            echo '<span class="mygold">' . $data['scoin'] . '</span>';
                        } else {
                            echo '<span class="mygold">0</span>';
                        }
                        ?>
                    </p>
                    <p>Chọn máy chủ để nạp vàng:
                        <select id="serverid" name="s" class="server">
                            <?php
                            if (!empty($data['listserver'])) {
                                foreach ($data['listserver'] as $server) {
                                    if (!empty($data['usersh']->id)) {
                                        //echo '<a target="_blank" href="http://linhvuong.sohaplay.vn/sv?server=' . $server['domain_server'] . '">' . $server['servername'] . '</a>';
                                        echo '<option value="' . $server['serverid'] . '"> ' . $server['servername'] . ' </option>';
                                    }
                                }
                            }
                            ?>

                        </select>
                    </p>
                    <div class="ratecom">
                        <p>
                            <input type="radio" class="select" value="20" name="coins">
                                <strong>20</strong> <span class="sohagold"></span><span class="symbol">=</span> <strong>200</strong><span class="goldicn"></span></p>
                        <p>
                            <input type="radio" class="select" value="50" name="coins">
                                <strong>50</strong> <span class="sohagold"></span><span class="symbol">=</span> <strong>500</strong><span class="goldicn"></span></p>
                        <p>
                            <input type="radio" class="select" value="120" name="coins">
                                <strong>120</strong> <span class="sohagold"></span><span class="symbol">=</span> <strong>1200</strong><span class="goldicn"></span></p>
                        <p>
                            <input type="radio" class="select" value="240" name="coins">
                                <strong>240</strong> <span class="sohagold"></span><span class="symbol">=</span> <strong>2400</strong><span class="goldicn"></span></p>
                        <p>
                            <input type="radio" class="select" value="480" name="coins">
                                <strong>480</strong> <span class="sohagold"></span><span class="symbol">=</span> <strong>4800</strong><span class="goldicn"></span></p>
                        <p>
                            <input type="radio" class="select" value="720" name="coins">
                                <strong>720</strong> <span class="sohagold"></span><span class="symbol">=</span> <strong>7200</strong><span class="goldicn"></span></p>
                    </div>
                    <p>Nhập số Scoin muốn nạp:
                        <input type="text" class="goldinput" id="coins" name="coins" />
                        <span class="symbolrate">=</span> <strong id="goldvalue"></strong><span name="goldtogame" class="goldicn" id="goldtogame"></span></p>
                    <div class="footcom"><a href="javascript://" id="loadbtn" class="loadbtn" ></a>

                        <div class="clr"></div>
                    </div>
                    <input type="hidden" name="userid" id ="userid" value="<?= $data['usersh']->id ?>" />
                    <input type="hidden" name="g" value="16" /> 
                    <input type="hidden" name="token" value="b9b86c94885d29c57a777ee039fbe994" />

                </form>


            </div>
            <div class="footer" style=" margin-top: 90px;">               
                <span style="color: #060000;">                            
                    Linh Vương Truyền Kỳ do Vĩnh Xuân phát hành độc quyền tại SohaPlay .</br>
                    Email : hotro@sohagame.vn - Điện thoại: 19006639 - Hỗ trợ , báo lỗi</br>
                    Địa chỉ: Tầng 19, tòa nhà Hapulico Center Building,</br>
                    Số 1 Nguyễn Huy Tưởng, Thanh Xuân, Hà Nội
                </span>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#loadbtn').click(function () {
                        var rd_scoin_selected = $('input[name=coins]:checked', '#myForm').val();
                        var input_scoin = $('#coins').val();
                        var serverid = $('#serverid').val();
                        var userid = $('#userid').val();
                        var scoin = 0;
                        if (typeof rd_scoin_selected != 'undefined') {
                            scoin = rd_scoin_selected;
                        } else if (typeof input_scoin != 'undefined') {
                            scoin = input_scoin;
                        } else {
                            alert("Số Scoin nhập chưa đúng.");
                        }
                        if ($.isNumeric(scoin) && scoin > 0) {
                            openPaymentWindows(scoin, serverid, userid);
                        } else {
                            popup('Số Scoin nhập chưa đúng.')
                            //alert("Số Scoin nhập chưa đúng.");
                        }
                    });
                    //slg_openMyModal2('/templates/manga_w/imgs/banner/s6.jpg',960,502);
                })
                $(function () {
                    $('input[name=coins]:checked', '#myForm').click(function () {
                        var rd_scoin_selected = $('input[name=coins]:checked', '#myForm').val();
                        alert(rd_scoin_selected);

                    });
                })
                function updatecoin(coin) {
                    $('#coins').val(coin);
                }
            </script>

            <script type="text/javascript">

                $(document).ready(function () {

                    // if user clicked on button, the overlay layer or the dialogbox, close the dialog	
                    $('a.btn-ok, #dialog-overlay, #dialog-box').click(function () {
                        $('#dialog-overlay, #dialog-box').hide();
                        return false;
                    });

                    // if user resize the window, call the same function again
                    // to make sure the overlay fills the screen and dialogbox aligned to center	
                    $(window).resize(function () {

                        //only do it if the dialog box is not hidden
                        if (!$('#dialog-box').is(':hidden'))
                            popup();
                    });


                });

                //Popup dialog
                function popup(message) {

                    // get the screen height and width  
                    var maskHeight = $(document).height();
                    var maskWidth = $(window).width();

                    // calculate the values for center alignment
                    var dialogTop = (maskHeight / 3) - ($('#dialog-box').height());
                    var dialogLeft = (maskWidth / 2) - ($('#dialog-box').width() / 2);

                    // assign values to the overlay and dialog box
                    $('#dialog-overlay').css({height: maskHeight, width: maskWidth}).show();
                    $('#dialog-box').css({top: dialogTop, left: dialogLeft}).show();

                    // display the message
                    $('#dialog-message').html(message);

                }

            </script>
            <div id="dialog-overlay"></div>
            <div id="dialog-box">
                <div class="dialog-content">
                    <div id="dialog-message"></div>
                    <a href="#" class="button">Close</a>
                </div>
            </div>

            <style type="text/css">

                #dialog-overlay {

                    /* set it to fill the whil screen */
                    width:100%; 
                    height:100%;

                    /* transparency for different browsers */
                    filter:alpha(opacity=50); 
                    -moz-opacity:0.5; 
                    -khtml-opacity: 0.5; 
                    opacity: 0.5; 
                    background:#000; 

                    /* make sure it appear behind the dialog box but above everything else */
                    position:absolute; 
                    top:0; left:0; 
                    z-index:3000; 

                    /* hide it by default */
                    display:none;
                }


                #dialog-box {

                    /* css3 drop shadow */
                    -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
                    -moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);

                    /* css3 border radius */
                    -moz-border-radius: 5px;
                    -webkit-border-radius: 5px;

                    background:#eee;
                    /* styling of the dialog box, i have a fixed dimension for this demo */ 
                    width:328px; 

                    /* make sure it has the highest z-index */
                    position:absolute; 
                    z-index:5000; 

                    /* hide it by default */
                    display:none;
                }

                #dialog-box .dialog-content {
                    /* style the content */
                    text-align:left; 
                    padding:10px; 
                    margin:13px;
                    color:#666; 
                    font-family:arial;
                    font-size:11px; 
                }

                a.button {
                    /* styles for button */
                    margin:10px auto 0 auto;
                    text-align:center;
                    background-color: #e33100;
                    display: block;
                    width:50px;
                    padding: 5px 10px 6px;
                    color: #fff;
                    text-decoration: none;
                    font-weight: bold;
                    line-height: 1;

                    /* css3 implementation :) */
                    -moz-border-radius: 5px;
                    -webkit-border-radius: 5px;
                    -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
                    -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
                    text-shadow: 0 -1px 1px rgba(0,0,0,0.25);
                    border-bottom: 1px solid rgba(0,0,0,0.25);
                    position: relative;
                    cursor: pointer;

                }

                a.button:hover {
                    background-color: #c33100;	
                }

                /* extra styling */
                #dialog-box .dialog-content p {
                    font-weight:700; margin:0;
                }

                #dialog-box .dialog-content ul {
                    margin:10px 0 10px 20px; 
                    padding:0; 
                    height:50px;
                }
            </style>
    </body>

</html>