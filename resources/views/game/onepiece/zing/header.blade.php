<div class="container top_menu">
    <a class="logo"></a>
    <div class="user">
        Xin chào, <b>{{ $name or 'bạn chưa đăng nhập' }}</b>
    </div>
    <div class="menu">
        <a style="color:#fef37a"
           href="http://appstore.zing.vn/game-nhap-vai/one-piece-online_24165.html?_src=as-old-link" target="_blank">Trang
            chủ</a> | <a href="http://me.zing.vn/forum/One%20Piece%20Online" target="_blank">Diễn đàn</a> | <a
                href="http://me.zing.vn/u/onepiecefpay" target="_blank">Fanpage</a>
        <span>
        @if (Session::has('zing_user'))
            {!! link_to(route('onepiece-zing.payment'), '') !!}
        @else
            <a href="javascript:login();"></a>
        @endif
        </span>
    </div>
</div>