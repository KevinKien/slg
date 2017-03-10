@extends('frontend_gift.layout')
    @section('title')
Gift Code
    @endsection
    @section('css_custum')
        <style>
            .hover{
                display: block;
            }
            .col-md-3{
                margin-right: 0px;
                padding-left: 7px;
                margin-bottom: 20px;

            }
            .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
                color: #fcfcfc!important;
                background-color: #e59403!important;
                cursor: default;
            }
            h4 {
                margin-bottom: 2px;
            }
        </style>
    @endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="carousel">
                                <a href="#"><img src="{!! asset('frontend_gift/images/sld/1.jpg') !!}" alt="Image 1" /></a>
                                <a href="#"><img src="{!! asset('frontend_gift/images/sld/2.jpg') !!}" alt="Image 2" /></a>
                                <a href="#"><img src="{!! asset('frontend_gift/images/sld/3.jpg') !!}" alt="Image 3" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="news-block">
                                <ul class="nav nav-tabs" style="margin-bottom: 20px;">
                                    <li class="active"><a href="#tanthu" data-toggle="tab" aria-expanded="true">Code Tân Thủ</a></li>
                                    <li class=""><a href="#user" data-toggle="tab" aria-expanded="false">Code User</a></li>
                                </ul>
                                <div id="TabContent" class="tab-content">
                                    <div class="tab-pane fade active in" id="tanthu">
                                        <div class="container">
                                    <div class="row">
                                        <?php $date = date('Y-m-d H:i:s'); ?>
                                        @foreach($event_new as $item)
                                            <?php  $game = App\Models\MerchantApp::where('id',$item->game_id)->first();
                                            $img = json_decode($game->images,true); ?>
                                            @if( strtotime($date) >= strtotime($item->time_min) && strtotime($date) <= strtotime($item->time_max))
                                                <div class="col-md-3">
                                                    {{--style="height: 200px;width: 300px"--}}
                                                    <a href="{!! route('event.detail',['id'=>$item->event_id]) !!}">
                                                        <img class="img-responsive" src="{!! $item->image !!}" alt="">
                                                    </a>
                                                    <div style="margin-top: 5px ;">
                                                        <img class="img-responsive" style="float: left;margin-right: 10px;" src="{!! $img['thumb'] !!}" alt="">
                                                        <h4><a href="{!! route('event.detail',['id'=>$item->event_id]) !!}">{!! $item->name !!}</a></h4>
                                                        <span>{!! $game->name !!}</span>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                    </div>
                                    <div class="tab-pane fade" id="user">
                                        <div class="container">
                                            <div class="row">
                                                <?php $date = date('Y-m-d H:i:s'); ?>
                                                @foreach($event_user as $item)
                                                    <?php  $game = App\Models\MerchantApp::where('id',$item->game_id)->first();
                                                    $img = json_decode($game->images,true); ?>
                                                    @if(strtotime($date) >= strtotime($item->time_min) && strtotime($date) <= strtotime($item->time_max))
                                                        <div class="col-md-3">
                                                            {{--style="height: 200px;width: 250px"--}}
                                                            <a href="{!! route('event.detail',['id'=>$item->event_id]) !!}">
                                                                <img class="img-responsive" src="{!! $item->image !!}" alt="">
                                                            </a>
                                                            <div style="margin-top: 5px ;">
                                                                <img class="img-responsive" style="float: left;margin-right: 10px;" src="{!! $img['thumb'] !!}" alt="">
                                                                <h4><a href="{!! route('event.detail',['id'=>$item->event_id]) !!}">{!! $item->name !!}</a></h4>
                                                                <span>{!! $game->name !!}</span>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js_custom')
    <script>
        $(document).ready(function() {
            $("#carousel").waterwheelCarousel({
                separation: 350,
                animationEasing: "linear"
            });
        });
    </script>
@endsection