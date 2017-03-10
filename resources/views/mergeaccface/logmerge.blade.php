@extends('app')

@section('htmlheader_title', 'Bảng chi tiết hợp tài khoản trùng id')

@section('main-content')

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
                            <th>Stt</th>
                            <th>Idfrom</th>
                            <th>Idto</th>
                            <th>Admin thực hiện</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logmerge as $key =>  $log)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $log->idfrom }}</td>
                            <td>{{ $log->idto }}</td>
                            <td>{{ $log->useradmin }}</td>
                            <td>{{ $log->status }}</td>
                            <td><a href="{{ route('merge-acc-face.backmerge', $log->id) }}"><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Back</button></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>
@endsection