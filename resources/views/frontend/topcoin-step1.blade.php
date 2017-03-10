@extends('frontend.pay-layout')

@section('title', 'Lựa chọn Game để chuyển Coin')

@section('pay-content')
    <div class="panel panel-default">
        <div class="panel-heading custom-panel-heading">Bước 1/2. Lựa chọn Game để chuyển Coin</div>
        <div class="panel-body">
            <?php $chunks = $games->chunk(2) ?>
            @foreach($chunks as $chunk)
                <div class="row">
                    @foreach($chunk as $game)
                        <div class="col-sm-6 col-md-6">
                            <div class="game-box">
                                <a href="{{ route('topcoin.game', ['slug' => $game->slug]) }}">
                                    <?php $images = json_decode($game->images) ?>
                                    <img alt="{{ $game->name }}" src="{{ $images->profile }}">
                                    <span class="game-logo" style="background:url('{{ $images->logo }}') "></span>

                                    <div class="game-info">
                                        <h4 class="text-bold">{{ $game->name }}</h4>
                                        <span class="text-uppercase">{{ ($game->gametype == 0) ? 'Web' : 'Mobile' }}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection