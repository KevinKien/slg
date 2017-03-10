{{--@if($errors->any())--}}
@if (count($errors) > 0)
        @foreach($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
        @endforeach
@endif

@if(Session::has('flash_error'))
    <p class="text-danger">{{ Session::get('flash_error') }}</p>
@endif

@if(Session::has('flash_success'))
    <p class="text-success">{{ Session::get('flash_success') }}</p>
@endif