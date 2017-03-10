@extends('frontend.master-layout')

@section('content')
<!-- slider games -->
<div class="hidden-xs container-fluid games">
    <div class="row">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
                <li data-target="#myCarousel" data-slide-to="3"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <a href="#" class="item active">
                    <img class="center-block img-responsive" src="{{ asset('/frontend/images/slide1.jpg', true) }}" alt="Chania">
                </a>
                <a href="#" class="item">
                    <img class="center-block img-responsive" src="{{ asset('/frontend/images/slide2.jpg', true) }}" alt="Chania">
                </a>
                <a href="#" class="item">
                    <img class="center-block img-responsive" src="images/slide3.jpg" alt="Flower">
                </a>
                <a href="#" class="item">
                    <img class="center-block img-responsive" src="images/slide4.jpg" alt="Flower">
                </a>
            </div>
            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<div class="container-fluid mobile-bg hidden-sm hidden-md hidden-lg" style="background-image: url('images/slgbg.jpg');"></div>

<div class="full-wraping">
    <!-- promotion container khung1 -->
    <div class="container promotion-container">
        <div class="row">
            <a href="#" class="col-xs-12 col-sm-6 col-padding-0">
                <div class="prot1 bg-prot">
                    <div class="prot2-img">
                        <img src="images/banner5.jpg">
                    </div>
                      <span class="protinfo1">
                            <h3>GIFTCODE KHỦNG</h3>
                            <p class="hidden-xs" id="charlimit40">Dummy text of the printing and typesetting industr
                                ....</p>
                            <span>30/12/2015</span>                                                                           
                      </span>
                </div>
            </a>

            <div class="col-xs-12 col-sm-6 col-padding-0">
                <div class="row">
                    <a href="#" class="col-xs-12 col-sm-6 col-padding-0">
                        <div class="prot2 bg-prot">
                            <div class="prot2-img">
                                <img src="images/banner2.jpg">
                            </div>
                            <div class="protinfo2 promotion2">
                                <h3>GÓI KHUYẾN MẠI KHỦNG</h3>

                                <p class="charlimit hidden-xs">Dummy text of the printing and cguyen nhgn ua lun</p>
                                <span>30/12/2015</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="col-xs-12 col-sm-6 col-padding-left-0">
                        <div class="prot3 bg-prot">
                            <div class="prot2-img">
                                <img src="images/banner5.jpg">
                            </div>
                            <div class="protinfo2 promotion3">
                                <h3>SỰ KIỆN TRONG TUẦN </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="row">
                    <a href="#" class="col-xs-12 col-sm-6 col-padding-0">
                        <div class="prot4 bg-prot">
                            <div class="prot2-img">
                                <img src="images/banner4.jpg">
                            </div>
                            <div class="protinfo2 promotion4">
                                <h3>MUA GẠCH ĐƯỢC VÀNG</h3>

                                <p class="charlimit hidden-xs">Dummy text of the printing and ....</p>
                                <span>30/12/2015</span>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="col-xs-12 col-sm-6 col-padding-left-0">
                        <div class="prot5 bg-prot" style="background-image:url(images/banner2.jpg);">
                            <div class="protinfo2 promotion5">
                                <h3>GAME MỚI KHUYẾN MẠI KHỦNG, RẤT NHIỀU CÁC VIP CODE CÙNG VỚI ƯU ĐÃI CHO GAME SOUL THẦN
                                    TIÊN MỚI RA MẮT TRONG TUẦN QUA</h3>
                                <span>30/12/2015</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection