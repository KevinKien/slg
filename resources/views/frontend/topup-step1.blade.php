@extends('frontend.pay-layout')

@section('title', 'Lựa chọn hình thức thanh toán')

@section('pay-content')
    <div class="panel panel-default">
        <div class="panel-heading custom-panel-heading">Bước 1: Lựa chọn hình thức thanh toán</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="thumbnail">
                        <div class="image-payment">
                            <img src="{{ asset('/frontend/images/card.png', true) }}" alt="Thanh toán bằng thẻ viễn thông">
                        </div>
                        <div class="caption">
                            <h3>Thẻ cào, Thẻ viễn thông</h3>

                            <p>Thanh toán tiện lợi với các loại thẻ cào di động Viettel, VinaPhone, Mobifone, GATE.</p>

                            <p><a role="button" class="btn btn-danger" href="{{ route('topupcash.telco') }}">Chọn</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="thumbnail">
                        <div class="image-payment">
                            <img src="{{ asset('/frontend/images/bank.png', true) }}"
                                 alt="Thanh toán bằng thẻ ATM, tín dụng.">
                        </div>
                        <div class="caption">
                            <h3>Thẻ ATM, Visa, Master, JCB...</h3>

                            <p>Thanh toán tiện lợi với thẻ ATM, thẻ tín dụng.  <a target="_blank"
                                                                                  href="//pay.slg.vn/huongdan.pdf"> Hướng
                                    dẫn</a></p>

                            <p><a role="button" class="btn btn-danger" href="{{ route('topupcash.bank') }}">Chọn</a></p>
                        </div>
                    </div>
                </div>
            </div>
            @if (Cache::get('settings_paygate_nganluong', 0) == 1)
                <div class="row">
                    <div class="col-sm-6">
                        <div class="thumbnail">
                            <div class="image-payment">
                                <img src="{{ asset('/frontend/images/nganluong.png', true) }}"
                                     alt="Thanh toán qua Ngân Lượng ATM">
                            </div>
                            <div class="caption">
                                <h3>Thẻ ATM, Visa, Master,..</h3>

                                <p>Thanh toán tiện lợi với các loại thẻ ATM, Visa, Master.</p>
                                <p style="color:red;">Chú ý: Giao dịch ATM có thu phí trực tiếp từ Ngân Lượng.</p>

                                <p><a role="button" class="btn btn-danger"
                                      href="{{ route('topupcash.get.nl') }}">Chọn</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection