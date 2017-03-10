<html>
<head>
    <style type="text/css">
        body{
            background: #000000;
            padding:0px;
            margin:0px;
        }
    </style>
    <link rel="stylesheet" href="https://app.slg.vn/fbapp/wheeloffortune/css/style.css">
    <script type="text/javascript">
        // the game itself
        var game;
        // the spinning wheel
        var wheel;
        // can the wheel spin?
        var canSpin;
        // the prize you are about to win
        var prize;
        // text field where to show the prize
        var prizeText;
        var degrees;
        var rounds;
        var countText;
        var count;
        var popup;
        var tween = null;
        var check;
        var giftcode;
        window.onload = function() {
            // creation of a 458x488 game

            game =  new Phaser.Game(500, 560, Phaser.CANVAS, 'canvas', { preload: preload, create: create, spin: spin, winPrize: winPrize});
            // adding "PlayGame" state
        }

        // PLAYGAME STATE
        function preload(){
            // preloading graphic assets
            game.load.image('wheel', '{{$image}}');
            game.load.image('pin','https://store-slg.cdn.vccloud.vn/fish/pin.png');
            game.load.image('background', 'https://store-slg.cdn.vccloud.vn/fish/wheel-pop-up-het-vong-quay.png');
            game.load.image('close', 'https://store-slg.cdn.vccloud.vn/manga/wheel_close.png');
        }
        // funtion to be executed when the state is created
        function create(){
            // giving some color to background
            game.stage.backgroundColor = "#3ecafd";
            // adding the wheel in the middle of the canvas
            wheel = game.add.sprite(game.width / 2, 250, "wheel");
            // setting wheel registration point in its center
            wheel.anchor.set(0.5);
            // adding the pin in the middle of the canvas
            var pin = game.add.sprite(game.width / 2, 250, "pin");
            // setting pin registration point in its center
            pin.anchor.set(0.5);

            // adding the text field
            prizeText = game.add.text(game.world.centerX, 510, "");
            // setting text field registration point in its center
            prizeText.anchor.set(0.5);
            // aligning the text to center
            prizeText.align = "center";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: 'https://app.slg.vn/wheel2',
                data: {data_ids:'{{$facebookid}}'},
            })
                    .done(function (data) {
                        countText = game.add.text(game.world.centerX, 540,"Lượt quay:"+ data["count"]);
                        countText.anchor.set(0.5);
                        countText.align = "center";
                        if(data["count"]==0){
                            check = "ok";
                        }
                    })
                    .fail(function () {
                        alert("error");
                    });
            // the game has just started = we can spin the wheel
            canSpin = true;
            // waiting for your input, then calling "spin" function
            game.input.onDown.add(this.spin, this);

            popup = game.add.sprite((game.width / 2) - 48, 250, 'background');
            popup.alpha = 0;
            popup.anchor.set(0.5);

            //  Position the close button to the top-right of the popup sprite (minus 8px for spacing)
            var pw = (popup.width / 2) - 30;
            var ph = (popup.height / 2) - 8;

            //  And click the close button to close it down again
            var closeButton = game.make.sprite(pw, -ph, 'close');
            closeButton.inputEnabled = true;
            closeButton.input.priorityID = 1;
            closeButton.input.useHandCursor = true;
            closeButton.events.onInputDown.add(closeWindow, this);

            //  Add the "close button" to the popup window image
            popup.addChild(closeButton);

            //  Hide it awaiting a click
            popup.scale.set(0.1);
        }
        // function to spin the wheel
        function spin(){
            // can we spin the wheel?
            if(canSpin){
                // resetting text field
                prizeText.text = "";
                if(check == "ok"){
                    openWindow();
                }else{
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: 'https://app.slg.vn/wheel1',
                    data: {data_ids:'{{$facebookid}}'},
                })
                        .done(function (data) {
                            if(data!=null){
                            count = "Lượt quay:"+ data["count"] ;
                            if(data["count"]== 0){
                                check = "ok";
                                rounds = data["rounds"];
                                degrees = data["degreesitem"];
                                prize= data["resultitem"];
                                giftcode = data["giftcode"];
                                canSpin = false;
                                // animation tweeen for the spin: duration 3s, will rotate by (360 * rounds + degrees) degrees
                                // the quadratic easing will simulate friction
                                var spinTween = game.add.tween(wheel).to({
                                    angle: 360 * rounds - degrees
                                }, 3000, Phaser.Easing.Quadratic.Out, true);
                                // once the tween is completed, call winPrize function
                                spinTween.onComplete.add(winPrize, this);
                            }
                            else{
                            rounds = data["rounds"];
                            degrees = data["degreesitem"];
                            prize= data["resultitem"];
                            giftcode = data["giftcode"];
                            canSpin = false;
                            // animation tweeen for the spin: duration 3s, will rotate by (360 * rounds + degrees) degrees
                            // the quadratic easing will simulate friction
                            var spinTween = game.add.tween(wheel).to({
                                angle: 360 * rounds - degrees
                            }, 3000, Phaser.Easing.Quadratic.Out, true);
                            // once the tween is completed, call winPrize function
                            spinTween.onComplete.add(winPrize, this);
                            }
                        }else{
                                alert("can't connect server");
                            }
                        })
                        .fail(function () {
                            alert("error");
                        });
                }
                // the wheel will spin round from 2 to 4 times. This is just coreography
                // then will rotate by a random number from 0 to 360 degrees. This is the actual spin
//                var degrees = game.rnd.between(46, 89);
                // before the wheel ends spinning, we already know the prize according to "degrees" rotation and the number of slices
//                prize = slices - 1 - Math.floor(degrees / (360 / slices));
                // now the wheel cannot spin because it's already spinning

            }
        }

        function check(){

        }
        function openWindow() {
            popup.alpha = 1.0;
            if ((tween != null && tween.isRunning) || popup.scale.x == 1)
            {
                return;
            }

            //  Create a tween that will pop-open the window, but only if it's not already tweening or open
            tween = game.add.tween(popup.scale).to( { x: 1, y: 1 }, 1000, Phaser.Easing.Elastic.Out, true);

        }

        function closeWindow() {
            popup.alpha = 0;
            if (tween && tween.isRunning || popup.scale.x == 0.1)
            {
                popup.alpha = 0;
                return;
            }

            //  Create a tween that will close the window, but only if it's not already tweening or closed
            tween = game.add.tween(popup.scale).to( { x: 0.1, y: 0.1 }, 500, Phaser.Easing.Elastic.In, true);

        }
        // function to assign the prize
        function winPrize(){
            // now we can spin the wheel again
            canSpin = true;
            countText.text = count;
            prizeText.text = prize;
            document.getElementById('myModal').style.display = "block";
            document.getElementById("showtext").innerHTML =
                    giftcode;
            // writing the prize you just won
        }

    </script>
    <script type="text/javascript" src="https://app.slg.vn/fbapp/wheeloffortune/css/phaser.min.js"></script>
    <script type="text/javascript" src="https://app.slg.vn/mtopup/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://app.slg.vn/fbapp/wheeloffortune/css/invite.js"></script>
</head>
<body>
<div style=" width: 100%; float: left; margin-bottom: 12px; color: #000;">
    @if(is_object(Auth::user()) &&  Auth::user()->name )
        <span>Xin chào, <b>{{ !empty(Auth::user()->fullname) ? Auth::user()->fullname : Auth::user()->name }}</b></span>
    @else
        <span>Xin chào, <b>Guest</b></span>
    @endif
</div>
<div style=" width: 100%; float: left; margin-bottom: 12px; color: white; margin-top: 0px;">
    <a href="#" class="card"></a>
    <div class="fb-like" data-href="https://apps.facebook.com/776842395785292" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
</div>
<div class="menu">
    <ul>
        <li style="background-image: url(https://app.slg.vn/fbapp/wheeloffortune/css/cong-quay-background_03.png);background-repeat: no-repeat; margin-left: 40px;"><a href="http://tieulong.slg.vn/" target="_blank"></a></li>
        <li style="background-image: url(https://app.slg.vn/fbapp/wheeloffortune/css/cong-quay-background_07.png);background-repeat: no-repeat;"><a href="#" onclick="invite();"></a></li>
        <li style="background-image: url(https://app.slg.vn/fbapp/wheeloffortune/css/cong-quay-background_11.png);background-repeat: no-repeat;"><a href="http://id.slg.vn/profile" target="_blank"></a></li>
        <li style="background-image: url(https://app.slg.vn/fbapp/wheeloffortune/css/cong-quay-background_15.png);background-repeat: no-repeat; margin-left: 40px;"><a href="#" onclick='return historywheel()'></a></li>
    </ul>
    <div id="canvas"></div>
</div>
    <footer>
        <p> Cty CP DV trực tuyến Vĩnh Xuân độc quyền phát hành tại Việt Nam
            Trụ sở: Tòa nhà Vĩnh Xuân. Số 39 Trần Quốc Toản, phường Trần Hưng Đạo, quận Hoàn Kiếm , thành phố Hà Nội
            Hotline: 043.5.380.202 số máy lẻ (101;103) - Email: hotro@slg.vn</p>
    </footer>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">x</span>
            <p id="showtext">Some text in the Modal..</p>
        </div>
    </div>

<div id="myModal2" class="modal2">
    <!-- Modal content -->
    <div class="modal-content2">

        <span class="close2">x</span>
        <h2>Lịch Sử Quay</h2>
        <table>
            <thead>
            <tr>
                <th>Item</th>
                <th>Code</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody id="history">

            </tbody>
        </table>
    </div>

</div>

<script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the button that opens the modal
        var btn = document.getElementById("canvas");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
            modal.style.opacity = "1";
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        var modal2 = document.getElementById('myModal2');

        // Get the button that opens the modal
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close2")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal2.style.display = "none";
            modal2.style.opacity = "1";
        }
        function historywheel()
        {
            document.getElementById('myModal2').style.display = "block";
            $.ajax({
                type: "POST",
                dataType: "json",
                url: 'https://app.slg.vn/wheel3',
                data: {data_ids:'{{$facebookid}}'},
            })
                    .done(function (data) {

                        var $options = '';
                        $.each(data, function (i, item) {
                            $options += '<tr><td >' + item.item + '</td>' + '<td >' + item.gift_code + '</td>' +'<td >' + item.created_at + '</td></tr>';
                        })

                        document.getElementById("history").innerHTML = $options;

                    })
                    .fail(function () {
                        alert("error");
                    })
        }



    </script>
</div>
</body>
</html>