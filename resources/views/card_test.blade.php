@extends('app')

@section('htmlheader_title', 'Thẻ cào Test')

@section('js-current')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Tạo thẻ Test</h3>
                </div><!-- /.box-header -->

                {!! Form::open([
                   'route' => 'card-test.create',
                   'class' => 'form-horizontal',
                ]) !!}

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('card-type', 'Loại thẻ *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select class="form-control" name="card-type" id="card-type">
                                <option value="Mobifone">Mobifone</option>
                                <option value="Vinaphone">Vinaphone</option>
                                <option value="Viettel">Viettel</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('quantity', 'Số lượng *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select class="form-control" name="quantity" id="quantity">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('amount', 'Mệnh giá *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select class="form-control" name="amount" id="amount">
                                <option value="10000">10.000</option>
                                <option value="20000">20.000</option>
                                <option value="30000">30.000</option>
                                <option value="50000">50.000</option>
                                <option value="100000">100.000</option>
                                <option value="200000">200.000</option>
                                <option value="300000">300.000</option>
                                <option value="500000">500.000</option>
                            </select>
                        </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <a href="javascript:history.back();" class="btn btn-default">Back</a>
                    {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
                </div><!-- /.box-footer -->

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Thẻ cào Test</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li {!! (empty($tab) || $tab == '1') ? 'class="active"' : '' !!}><a data-toggle="tab" href="#tab_1">Thẻ chưa dùng</a></li>
                            <li {!! ($tab == '2') ? 'class="active"' : '' !!}><a data-toggle="tab" href="#tab_2">Thẻ đã dùng</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab_1" class="tab-pane {{ (empty($tab) || $tab == '1') ? 'active' : '' }}">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Mã thẻ</th>
                                        <th>Số Seri</th>
                                        <th>Loại</th>
                                        <th>Mệnh giá (VND)</th>
                                        <th>Tạo bởi</th>
                                        <th>Tạo lúc</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($cards as $card)
                                        <tr>
                                            <td>{{ $card->card_code }}</td>
                                            <td>{{ $card->card_seri }}</td>
                                            <td>{{ strtoupper($card->card_type) }}</td>
                                            <td>{{ number_format($card->amount, 0, ',', '.') }}</td>
                                            <td>{!! link_to_route('user.edit', $card->creator->name, ['id' => $card->created_by]) !!}</td>
                                            <td>{{ date('d/m/Y H:i:s', $card->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer">
                                    {!! $cards->appends(['tab' => 1])->render() !!}
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div id="tab_2" class="tab-pane {{ ($tab == '2') ? 'active' : '' }}">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Mã thẻ</th>
                                        <th>Số Seri</th>
                                        <th>Loại</th>
                                        <th>Mệnh giá (VND)</th>
                                        <th>Dùng bởi</th>
                                        <th>Dùng lúc</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($issued_cards as $card)
                                        <tr>
                                            <td>{{ $card->card_code }}</td>
                                            <td>{{ $card->card_seri }}</td>
                                            <td>{{ strtoupper($card->card_type) }}</td>
                                            <td>{{ number_format($card->amount, 0, ',', '.') }}</td>
                                            <td>{!! link_to_route('user.edit', $card->issuer->name, ['id' => $card->issued_by]) !!}</td>
                                            <td>{{ date('d/m/Y H:i:s', $card->issued_at) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer">
                                    {!! $issued_cards->appends(['tab' => 2]) !!}
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection