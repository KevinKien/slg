<!-- games list-->
<div class="games-container">
    <div class="container">
        <div class="list-games" id="games">
            <div class="row">
                <h2>SLG's GAMES</h2>
            </div>
            <div class="row">
                @foreach($games as $game)
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="game-container center-block">
                        <?php $images = json_decode($game['images']) ?>
                            <img class="img-responsive center-block" src="{{ $images->logo }}">

                            <h3>{{ $game['name'] }}</h3>

                            <p>{{ $game['user_num'] }}+ lượt chơi</p>

                            <div class="rating">
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                            </div>
                            <a target="_blank" href="{{ $game['url_homepage'] }}" class="choingay-button">&nbsp;CHƠI NGAY</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<footer class="container-fluid slg-footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <img class="img-responsive img-logo center-block"
                             src="{{ asset('frontend/images/logofull.png', true) }}">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="row">
                    <h2 class="center-block-footer">LIÊN HỆ</h2>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12"><span class="glyphicon glyphicon-envelope"></span>

                        <p>hotro@slg.vn</p></div>
                    <div class="col-xs-12 col-sm-12"><span class="glyphicon glyphicon-earphone"></span>

                        <p>043.5.380.202</p></div>
                    <div class="col-xs-12 col-sm-12"><span class="glyphicon glyphicon-map-marker"></span>

                        <p>Tầng 5, Tòa Nhà Vĩnh Xuân, 39 Trần Quốc Toản, Hoàn Kiếm, Hà Nội</p></div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="row">
                    <h2 class="center-block-footer">SOCIAL NETWORK</h2>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 social-network">
                        <a href="#"><span class="social-nw fb-square fa fa-facebook-square"></span></a>
                        <a href="#"><span class="social-nw fa gplus-square fa-google-plus-square"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 license">
                2015 SLG Game. All rights reserved.
            </div>
        </div>
    </div>
</footer>
@yield('additional-script')