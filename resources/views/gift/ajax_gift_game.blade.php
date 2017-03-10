@if(sizeof($server_game) > 0)
    <option id="server_all" value="0">Chọn tất cả</option>
    @foreach($server_game as $item)
        <option class="server_id"  value="{!! $item->serverid !!}">— {!! $item->servername !!}</option>
    @endforeach
@endif
