<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->

            @if(Auth::user()->is('administrator|cp|partner|deploy'))
            <li><a href="{{ url('home') }}"><i class='fa fa-link'></i> <span>Home</span></a></li>
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>User active</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/daulog">DAU</a></li>
                    <li><a href="#">MAU</a></li>
                </ul>
            </li>
            @endif

            @if(Auth::user()->is('administrator'))
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Manage</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/server">Server</a></li>
                    <li><a href="/cpid">CPID</a></li>
                    <li><a href="/partner">Partner</a></li>
                    <li><a href="/merchant_app">Game</a></li>
                    <li><a href="/oauth_client_endpoints">Oauthent client</a></li>
                    <li><a href="/merchant_app_product">Product</a></li>
                    <li><a href="/merchant_app_product_apple">Product_Apple</a></li>
                </ul>
            </li> 
             <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Doanh thu</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/log_zing">Zing</a></li>
                    <li><a href="/log_soha">Soha</a></li>
                    <li><a href="/log_fpay">Fpay</a></li>
                    <li><a href="/log_facebook">Facebook</a></li>
                    <li><a href="/log_mwork">Mwork</a></li>
                    <li><a href="/log_garena">Garena</a></li>
                </ul>
            </li>
            <li><a href="{{ url('users') }}"><i class="fa fa-link"></i><span>User</span></a></li>
            @endif

            @if(Auth::user()->is('administrator|deploy'))
                <li><a href="{{ url('push-news') }}"><i class="fa fa-link"></i><span>Cập nhật tin tức</span></a></li>
            @endif

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
