@extends('app')

@section('htmlheader_title', 'Thêm id tài khoản test')

@section('main-content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Add id Account</h3>
                </div><!-- /.box-header -->

                {!! Form::open([
                   'route' => 'accounttest.insert',
                   'class' => 'form-horizontal',
                   'method' => 'post'
                ]) !!}

                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('keyword', 'ID Account Test *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('keyword', Request::get('keyword'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('keyword', 'Game', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::select('game', $listgame, ['class'=> 'form-control']) !!}
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
    @if(isset($account))
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Account Table</h3>
                            <div class="box-tools pull-right col-md-3" >
                                {!! Form::open([
                                      'route' => 'accounttest.search',
                                      'class' => 'form-horizontal',
                                      'method' => 'get'
                                   ]) !!}
                                <div class="form-group" style="margin: 0 85px 0 0;">
                                {!! Form::select('game1', $listgame, $game1, ['class'=> 'form-control'] ) !!}
                                </div>
                            </div>
                            <div class="box-tools pull-right col-md-1">
                            {!! Form::submit('Search', ['class' => 'btn btn-info pull-right btn-sm']) !!}
                            {!! Form::close() !!}
                            </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="user-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Game</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0;?>
                            @foreach ($account as $user)
                                <?php $i++;
                                $tt=$page*10-10+$i;?>
                                <tr>
                                    <td>{{ $tt }}</td>
                                    <td>{{ $user->test_id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td><a href="{{ route('accounttest.delete', $user->id) }}" onclick='return confirmSubmit()'><button class="btn btn-default btn-xs"><i></i> Delete</button></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        {!! $account->render() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script LANGUAGE="JavaScript">
        function confirmSubmit()
        {
            var agree=confirm("Bạn có muốn xóa mục này không? Nếu xóa thì dữ liệu xẽ bị mất");
            if (agree)
                return true ;
            else
                return false ;
        }
    </script>
@endsection