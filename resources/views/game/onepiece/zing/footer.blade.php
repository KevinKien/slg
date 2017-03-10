@if(Session::has('alert'))
    <script>alert('{{ Session::get('alert') }}')</script>
    <?php Session::forget('alert') ?>
@endif
@yield('additional-footer')
