@extends('payment.web.layout_payweb')

@section('htmlheader_title')
    Nạp Coin vào Game
@endsection

@section('css-current')
    <link type="text/css" rel="stylesheet" href="{{ url('portal/css/cs.css') }}">
    <link href="/portal/css/coinstyle.css" type="text/css" rel="stylesheet"/>
@endsection

@section('content')
    <section class="ContsSubBox v01_layout float_right">
        <h3 class="big floating_left payinfo_header">Nạp Coin vào Game</h3>
        <div class="space15"></div>
        <div class="infomation_select doixu_select">
            <?php
//            $chunks = $games->chunk(2);
//            print_r($chunks);
            ?>
            @foreach($games as $game)
            <?php $images = json_decode($game->images); ?>
            <a href="{{ route('topcoin.game', ['slug' => $game->slug]) }}" class="infomation_blocking game-box">
                <span class="info_iilustration" style="background:url('{{ $images->profile }}') "></span>
                <span class="logo_doixu" style="background:url({{ $images['4'] }}) "></span> 
                <span class="info_name"> 
                    <strong>{{ $game->name }}</strong> 
                    <b><?php echo $game->gametype == 0 ? 'WEB':'MOBILE'; ?></b> 
                </span> 
            </a>
            @endforeach
        </div>
  
    </section>
@endsection
