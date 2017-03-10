@extends('payment.mobile.layout_paymobile')

@section('htmlheader_title')
Nạp Coins
@endsection

@section('content')<!--
<div class="row top-bar">
    <button class="toggle-menu menu-left push-body"><span class="glyphicon glyphicon-menu-hamburger"></span>
    </button>
    <span><strong>ID</strong><thin>SLG</thin></span>
</div>-->
<div class="row">
    <div class="user-container">
        <div class="user-dp">
            <a href="#">
                @if(!empty(Auth::user()->avatar))
                <img src="{{Auth::user()->avatar}}" width="40" height="40" border="0"/>
                @else
                <img src="//pay.slg.vn/mtopup/images/default-avatar.png" border="0"/>
                @endif

            </a>
            <span class="user-name">{{Auth::user()->name}}</span>
            <span class="user-coin"><a href="#">Tài khoản: {{$user_coins}} Coin</a></span>
            <!--<button role="button" class="btn btn-default pull-right">THOÁT</button>-->
        </div>
    </div>

    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            @if(!$inreview_status)
            <li class="active tab-xu"><a href="#card" data-toggle="tab">Mua bằng Coin</a></li>
                @if($client_id == '8633283045' && Auth::user()->id == '5391321' )
                <li class="tab-xu"><a href="#bank" data-toggle="tab">Payment In App</a></li>
                @endif
            @else
            <li class="active tab-xu"><a href="#bank" data-toggle="tab">Payment In App</a></li>
            @endif

        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            @if(!$inreview_status)
            <div class="tab-pane active" id="card">
                <div class="pack-cnt">
                    <div class="coin-p">
                        @foreach ($get_app_items as $app_item)
                        <div class="coin-pack">
                            <span class="coin-detail">
                                <strong>{{$app_item->item_name}}</strong><br>
                                @if(!empty($app_item->image))
                                <img src="{{$app_item->image}}" height="75" width="75" border="0"/>
                                @else
                                <img src="//pay.slg.vn/mtopup/images/coin.png" border="0"/>
                                @endif
                            </span>
                            @if($user_coins >= $app_item->item_price )
                            <a href="{{$callservice_link.'&price='.$app_item->item_price}}" role="button" class="btn btn-danger btn-block">{{$app_item->item_price}} Coin</a>
                            @else
                            <a href="{{$topupcash_link}}" role="button" class="btn btn-primary btn-block">{{$app_item->item_price}} - NẠP COIN</a>
                            @endif
                        </div>

                        @endforeach

                        <div class="coin-pack">
                            <span class="coin-detail">
                                <strong>Nạp thêm Coin</strong><br>
                                <img src="//pay.slg.vn/mtopup/images/coin.png" border="0"/>
                            </span>
                            <a href="{{$topupcash_link}}" role="button" class="btn btn-primary btn-block">NẠP COIN</a>
                        </div>
                    </div>
                </div>
            </div>
            @if($client_id == '8633283045' && Auth::user()->id == '5391321' )
            <div class="tab-pane" id="bank">
                <div class="pack-cnt">
                    <div class="coin-p">
                        @foreach ($itemlist as $item)
                        <div class="coin-pack">
                            <span class="coin-detail">
                                <strong>{{$item->title}}</strong><br>
                                @if(!empty($item->image))
                                <img src="{{$item->image}}" height="75" width="75" border="0"/>
                                @else
                                <img src="//pay.slg.vn/mtopup/images/coin.png" border="0"/>
                                @endif
                            </span>
                            <a href="{{$callserviceapple_link.'&product_apple_id='.$item->product_apple_id}}" role="button" class="btn btn-danger btn-block">{{$item->amount}} $</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endif
            @if($inreview_status)
            <div class="tab-pane active" id="bank">
                <div class="pack-cnt">
                    <div class="coin-p">
                        @foreach ($itemlist as $item)
                        <div class="coin-pack">
                            <span class="coin-detail">
                                <strong>{{$item->title}}</strong><br>
                                @if(!empty($item->image))
                                <img src="{{$item->image}}" height="75" width="75" border="0"/>
                                @else
                                <img src="//pay.slg.vn/mtopup/images/coin.png" border="0"/>
                                @endif
                            </span>
                            <a href="{{$callserviceapple_link.'&product_apple_id='.$item->product_apple_id}}" role="button" class="btn btn-danger btn-block">{{$item->amount}} $</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="tab-pane " id="bank">
                <div class="pack-cnt">
                    <div class="coin-p">
                        @foreach ($itemlist as $item)
                        <div class="coin-pack">
                            <span class="coin-detail">
                                <strong>{{$item->title}}</strong><br>
                                @if(!empty($item->image))
                                <img src="{{$item->image}}" height="75" width="75" border="0"/>
                                @else
                                <img src="//pay.slg.vn/mtopup/images/coin.png" border="0"/>
                                @endif
                            </span>
                            <a href="{{$callserviceapple_link.'&product_apple_id='.$item->product_apple_id}}" role="button" class="btn btn-danger btn-block">{{$item->amount}} $</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif




        </div>
    </div>
</div>

@endsection