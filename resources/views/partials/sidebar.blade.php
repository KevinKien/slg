<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            {{--<li class="header">Menu</li>--}}
            <!-- Optionally, you can add icons to the links -->

            @if(Auth::user()->is('administrator|cp|partner|deploy'))
                <li><a href="{{ url('/') }}"><i class='fa fa-link'></i> <span>Trang chủ</span></a></li>
                <li class="treeview">
                    <a href="#"><i class='fa fa-link'></i> <span>Log Người dùng</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/daulog">DAU</a></li>
                        <li><a href="/logniu">NIU</a></li>
                        <li><a href="#">MAU</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('revenue.index') }}"><i class='fa fa-line-chart'></i> <span>Biểu đồ doanh thu</span></a>
                <li><a href="{{ route('revenue-topup') }}"><i class='fa fa-bar-chart'></i> <span>Biểu đồ doanh thu Topup</span></a>
            @endif
 
            @if(Auth::user()->is('administrator'))
                <li class="treeview">
                    <a href="#"><i class='fa fa-link'></i> <span>Quản lý</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/server">Server</a></li>
                        <li><a href="/cpid">CPID</a></li>
                        <li><a href="/partner">Partner</a></li>
                        <li><a href="/merchant_app">Game</a></li>
                        <li><a href="/oauth_client_endpoints">Oauthent client</a></li>
                        <li><a href="/merchant_app_product">Product</a></li>
                        <li><a href="/merchant_app_product_apple">Product_Apple</a></li>
                        <li><a href="/wheel">Wheel item</a></li>
                        <li><a href="/gift-code-list">Gift Code</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class='fa fa-usd'></i> <span>Doanh thu</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/log_zing">Zing</a></li>
                        <li><a href="/log_soha">Soha</a></li>
                        <li><a href="/log_fpay">Fpay</a></li>
                        <li><a href="/log_fpay1">Fpay-More</a></li>
                        <li><a href="/log_facebook">Facebook</a></li>
                        <li><a href="/log_mwork">Mwork</a></li>
                        <li><a href="/log_garena">Garena</a></li>
                        <li><a href="/log_coin">Coin</a></li>
                        <li><a href="/log_transfer_coin">Transfer-Coin</a></li>
                        <li><a href="/log_detail_transfer">Detail-Transfer</a></li>
                        <li><a href="/log_buy_item">Detail-buy-items</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><i class='fa fa-gears'></i> <span>Công cụ</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('users') }}"><i class="fa fa-users"></i><span>Tra cứu Người dùng</span></a></li>
                        <li><a href="{{ route('user.fix.get') }}"><i class="fa fa-users"></i><span>Ghép người dùng FPAY</span></a></li>
                        <li><a href="{{ url('compensations') }}"><i class="fa fa-money"></i><span>Bù Coin</span></a></li>
                        <li><a href="{{ url('merge-acc-face') }}"><i class="fa fa-credit-card"></i><span>Hợp acc facebook</span></a></li>
                        <li><a href="{{ route('card-test.index') }}"><i class="fa fa-credit-card"></i><span>Thẻ cào Test</span></a></li>
                        <li><a href="{{ route('scratch-card-transaction') }}"><i class="fa fa-credit-card"></i><span>Tra cứu giao dịch PayDirect</span></a></li>
{{--                        <li><a href="{{ route('card-pending.index') }}"><i class="fa fa-warning"></i><span>Giao dịch thẻ cào nghi vấn</span></a></li>--}}
                        <li><a href="{{ url('accounttest') }}"><i class="fa fa-users"></i><span>Thêm tài khoản test</span></a></li>
                        <li><a href="{{ url('blocked-payment') }}"><i class="fa fa-users"></i><span>Danh sách chặn payment</span></a></li>
                      
                        @if(Auth::user()->is('administrator|deploy'))
                            {{--<li><a href="{{ url('push-news') }}"><i class="fa fa-link"></i><span>Cập nhật tin tức</span></a></li>--}}
                            <li><a href="{{ url('notification') }}"><i class="fa fa-link"></i><span>Gửi thông báo</span></a></li>
                            <li><a href="{{ url('marketingmail') }}"><i class="fa fa-link"></i><span>Gửi Mail</span></a></li>
                        @endif
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><i class='fa fa-flag'></i> <span>Log</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('logGD') }}"><i class="fa fa-flag"></i><span>Log giao dịch</span></a></li>
                        <li><a href="{{ route('transfer-coin-log') }}"><i class="fa fa-flag"></i><span>Log chuyển Coin</span></a></li>
                        <li><a href="{{ url('add-coin-log') }}"><i class="fa fa-flag"></i><span>Log bù Coin</span></a></li>
                        @if(in_array(Auth::user()->name, ['caovuong', 'iphone', 'nbp85hn@gmail.com', 'tieumai93']))
                            <li><a href="{{ url('request-log') }}"><i class="fa fa-flag"></i><span>Log truy cập</span></a></li>
                            <li><a href="{{ url('laravel-log') }}"><i class="fa fa-flag"></i><span>Log Laravel</span></a></li>
                        @endif
                    </ul>
                </li>
                    @if(in_array(Auth::user()->name, ['caovuong', 'iphone', 'nbp85hn@gmail.com', 'tieumai93']))
                        <li><a href="{{ url('settings') }}"><i class="fa fa-gears"></i><span>Tùy chỉnh</span></a></li>
                    @endif
            @endif
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
