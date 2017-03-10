@extends('app')

@section('htmlheader_title', 'Tìm kiếm người dùng')

@section('css-current')
{{--{!! HTML::style('plugins/datatables/dataTables.bootstrap.css') !!}--}}
@endsection

@section('js-current')
{{--	{!! HTML::script('plugins/datatables/jquery.dataTables.min.js') !!}--}}
{{--	{!! HTML::script('plugins/datatables/dataTables.bootstrap.min.js') !!}--}}
{{--{!! HTML::script('js/user.js') !!}--}}
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Search User</h3>
            </div><!-- /.box-header -->

            {!! Form::open([
            'route' => 'compensation.search',
            'class' => 'form-horizontal',
            'method' => 'GET'
            ]) !!}

            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('keyword', 'UserName *', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-5">
                        {!! Form::text('keyword', Request::get('keyword'), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('keyword', 'Partner', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        <select id='part' class='form-control' name='part'>
                            <?php
                            $option = '';
                            $option.="<option value='SLG'>SLG</option>";
                            foreach($listpart as $acc){
                                if($acc->partner_name == $part1){
                                    $option.="<option value='$acc->partner_name' selected='selected'>".$acc->partner_name."</option>";
                                }else{
                                    $option.="<option value='$acc->partner_name'>".$acc->partner_name."</option>";}
                            }
                            print $option;
                            ?>

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

@if(isset($users))
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
                            <th>FID</th>
                            <th>Nhà cung cấp</th>
                            <th>Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->fid }}</td>
                            <td>{{ $user->provider }}</td>
                            <td><a href="{{ route('compensation.edit', $user->id) }}"><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Bù Coin</button></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer">
                {!! $users->appends(['keyword' => Request::get('keyword')])->appends(['part' => $part1])->render() !!}
            </div>
        </div>
    </div>
</div>
@endif
@endsection