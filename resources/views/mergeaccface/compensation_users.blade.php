@extends('app')

@section('htmlheader_title', 'Tìm kiếm người dùng')

@section('css-current')
{{--{!! HTML::style('plugins/datatables/dataTables.bootstrap.css') !!}--}}
@endsection

@section('js-current')
{{--	{!! HTML::script('plugins/datatables/jquery.dataTables.min.js') !!}--}}
{{--	{!! HTML::script('plugins/datatables/dataTables.bootstrap.min.js') !!}--}}
{{--{!! HTML::script('js/user.js') !!}--}}
<script>
    $('document').ready(
        function(){
            $("input[name='idfrom']").click(function(){
                $('input[id="to'+$(this).val()+'"]').prop('checked', false);
                $('input[name="idto"]').attr('disabled', false);
                $('input[id="to'+$(this).val()+'"]').attr('disabled', 'disabled');
                }
            ); 
            $("input[name='idto']").click(function(){
                $('input[id="from'+$(this).val()+'"]').prop('checked', false);
                $('input[name="idfrom"]').attr('disabled', false);
                $('input[id="from'+$(this).val()+'"]').attr('disabled', 'disabled');
                }
            );
            $( "#mergeacc" ).click(function(e) {
                if($("input:radio[name='idfrom']").is(":checked")) {
                    if($("input:radio[name='idto']").is(":checked")) {
                        return true;
                     }else{
                        alert("Chọn tài khoản đích");
                    }
                 }else{
                     alert("Chọn tài khoản nguồn");
                 }
                 return false;
            });
        }
    );
</script>
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Merge acc face</h3>
            </div><!-- /.box-header -->

            {!! Form::open([
            'route' => 'merge-acc-face.search',
            'class' => 'form-horizontal',
            'method' => 'GET'
            ]) !!}

            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('keyword', 'UserName 1 *', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-5">
                        {!! Form::text('keyword1', Request::get('keyword1'), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('keyword', 'UserName 2 *', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-5">
                        {!! Form::text('keyword2', Request::get('keyword2'), ['class' => 'form-control']) !!}
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
@if(isset($users1) or isset($users2) )
{!! Form::open([
            'route' => 'merge-acc-face.update',
            'method' => 'POST'
            ]) !!}
<?php //echo "<pre>"; print_r($users); echo "</pre>"; die;?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">User Table</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table id="user-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($users1))
                        @foreach ($users1 as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                           <input type="radio" name="idfrom" id="from{{$user->id}}" value="{{$user->id}}" <?php if(strpos($user->name,"@fb")>0){ echo 'disabled="disabled"';}else{ echo 'checked=""'; } ?> >
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="idto" id="to{{$user->id}}" value="{{$user->id}}"  >
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @if(isset($users2))
                        @foreach ($users2 as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <!--<td><a href="{{ route('compensation.edit', $user->id) }}"><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Bù Coin</button></a></td>-->
                            <td>
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="idfrom" id="from{{$user->id}}" value="{{$user->id}}" <?php if(strpos($user->name,"@fb")>0){ echo 'disabled="disabled"';} ?>  >
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="idto" id="to{{$user->id}}" value="{{$user->id}}" checked="">
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer">
                {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right', 'id'=>'mergeacc']) !!}
            </div><!-- /.box-footer -->

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endif

@endsection