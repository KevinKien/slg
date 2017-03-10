@extends('payment.web.layout_payweb')

@section('htmlheader_title')
    Nạp Coin vào Game {{ $game->name }}
@endsection

@section('content')
    <section class="ContsSubBox v01_layout float_right">
        <h3 class="big floating_left">Nạp Coin vào Game {{ $game->name }}</h3>
        <div class="space15"></div>
        <div class="space15"></div>
        <table width="100%" cellspacing="0" cellpadding="0" class="tableStyle02 pg_table">
            <tbody>
            <tr>
                <th class="borderStyle v01_th_sty hide_row" style="width: 100%">&nbsp;</th>
                <th class="borderStyle v01_th_sty hide_row">&nbsp;</th>
            </tr>
            <tr>
                <td class="borderStyle" style="border:none;vertical-align:top">
                    <div class="game-box clear">
                        <?php $images = json_decode($game->images); ?>
                        <h2>{{ $game->name }}</h2>
                        <img alt="{{ $game->name }}" src="{{ $images->profile }}">
                    </div>
                </td>
                <td style="border:none" class="borderStyle">
                    {!! Form::open([
                       'route' => ['topcoin.transfer', $game->slug],
                       'class' => 'form-horizontal'
                    ]) !!}

                        <div class="form-buy cutebox cw364">
                            {{--<div class="form-row cutebox cw260">--}}
                                {{--<div class="c-left"><label class="pad-left">Số Coin hiện có:</label></div>--}}
                                {{--<div class="c-right"><label class="pad-left text-info">{{ is_null($cash) ? 0 : $cash->coins }}</label></div>--}}
                            {{--</div><!--//form-row-->--}}
                            <div class="form-row cutebox cw260">
                                <div class="c-left"><label for="server" class="pad-left">Chọn máy chủ *:</label></div>
                                <div class="c-right">
                                    <select id="server" name="server" class="selectbox-pop textbox-pop">
                                        @if(!empty($servers))
                                            @foreach ($servers as $server)
                                                <option value="{{ $server['serverid'] }}"{{ ($server['serverid'] == old('server')) ? ' selected=selected' : '' }}>{{ $server['servername'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div><!--//form-row-->
                            <div class="form-row cutebox cw260">
                                <div class="c-left"><label for="coin" class="pad-left">Số Coin muốn nạp *:</label></div>
                                <div class="c-right">
                                    {!! Form::text('coin', old('coin'), ['class' => 'textbox-pop']) !!}
                                </div>
                            </div><!--//form-row-->

                            @include('payment.web.message')

                        </div><!--//cw363-->
                        <div class="group-btn">
                            <label class="btn-gray" style="margin-left: 0"><input type="button" id="back" name="back" onclick="location.href='{{ route('topcoin') }}'" value="Quay lại"></label>
                            <label class="btn-green"><input type="submit" id="submit_btn" value="Đổi Coin"></label>
                        </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            </tbody>
        </table>
    </section>
@endsection
