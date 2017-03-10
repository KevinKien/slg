@extends('frontend.pay-layout')

@section('title', 'Chuyển Coin vào Game')

@section('additional-script')
	<script src='https://www.google.com/recaptcha/api.js?hl=vi'></script>
    <script>
        $('#btn-submit').on('click', function () {
            $(this).attr('disabled', 'disabled');
            $('#frm').submit();
        });
    </script>
@endsection

@section('pay-content')
    <div class="panel panel-default">
        <div class="panel-heading custom-panel-heading">Bước 2/2. Chuyển Coin vào Game "{{ $game->name }}"</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-5">
                    <div class="game-box">
                        <a>
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
                <div class="col-sm-7">
                    @include('frontend.partials.message')

                    {!! Form::open([
                       'route' => ['topcoin.transfer', $game->slug],
                       'class' => 'form-horizontal',
                       'id'    => 'frm'
                    ]) !!}
				
                    <div class="form-group {{ $errors->first('server') ? 'has-error' : '' }}">
                        <label for="server" class="col-sm-5 control-label">Máy chủ <span
                                    class="text-danger">*</span></label>

                        <div class="col-sm-7">
                            <select class="form-control" name="server" id="server">
                                <option value="">Chọn máy chủ</option>
                                @if(Auth::user()->is('administrator'))
                                    <option value="0">Server 0</option>
                                @endif

                                @if(!empty($servers))
                                    @foreach ($servers as $server)
                                        <option value="{{ $server['serverid'] }}"{{ ($server['serverid'] == Request::input('s') || $server['serverid'] == old('server')) ? ' selected=selected' : '' }}>{{ $server['servername'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->first('coin') ? 'has-error' : '' }}">
                        <label for="coin" class="col-sm-5 control-label">Số Coin muốn chuyển <span
                                    class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-control" name="coin" id="coin">
                                @foreach ($coins as $coin)
                                    <option value="{{ $coin }}"{{ $coin == old('coin') ? ' selected=selected' : '' }}>{{ $coin }} Coins</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
					
					{{--<div class="form-group">--}}
						{{--<label class="col-sm-4 control-label">Xác thực <span--}}
									{{--class="text-danger">*</span></label>--}}
						{{--<div class="col-sm-8">--}}
							{{--<div class="g-recaptcha" data-sitekey="6LfDGBMTAAAAAMaKjZqtAMvD7yjHIrj3h16kShZO"></div>--}}
						{{--</div>--}}
					{{--</div>--}}

                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-7">
                            <button type="button" class="btn btn-default"
                                    onclick="location.href='{{ route('topcoin') }}'">Quay lại
                            </button>
                            <button id="btn-submit" type="button" class="btn btn-danger">Đổi Coin</button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection