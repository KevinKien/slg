
@extends('app')

@section('htmlheader_title', 'Thay đổi thông tin người dùng')

@section('js-current')
    <script>
        $('select[name="role"]').change(function () {
            if ($(this).val() == 'partner') {
                $('#partner-box').show();
                $('#app-box').hide();
            } else if ($(this).val() == 'deploy') {
                $('#app-box').show();
                $('#partner-box').hide();
            } else {
                $('#app-box').hide();
                $('#partner-box').hide();
            }
        });
        $('select[name="role"]').trigger('change');
    </script>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">Edit User</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($user, [
                    'route' => ['user.update', $user->id],
                    'class' => 'form-horizontal'
                ]) !!}
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('name', 'Name *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('role', 'Role *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select name="role" class="form-control">
                                <option value="">None</option>
                                @foreach ($roles as $role)
                                    <?php $selected = $user->is($role->slug) ? ' selected=selected' : ''; ?>
                                    <option value="{{ $role->slug }}"{{ $selected }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="partner-box" class="form-group" style="display: none;">
                        {!! Form::label('partner_id', 'Partner *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select name="partner_id" class="form-control">
                                @foreach ($partners as $partner)
                                    <?php $selected = ($partner->partner_id === $user->partner_id) ? ' selected=selected' : ''; ?>
                                    <option value="{{ $partner->partner_id }}"{{ $selected }}>{{ $partner->partner_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="app-box" class="form-group" style="display: none;">
                        {!! Form::label('app_id', 'Game *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <select name="app_id" class="form-control">
                                @foreach ($apps as $app)
                                    <?php $selected = ($app->id === $user->app_id) ? ' selected=selected' : ''; ?>
                                    <option value="{{ $app->id }}"{{ $selected }}>{{ $app->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'Email *', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', 'Phone', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('identify', 'CMND', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('identify', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>                    
                    <div class="form-group">
                        {!! Form::label('password', 'Password', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('passwordconfirm', 'Confirm Password', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-5">
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="active" {{ $user->active == 1 ? 'checked' : '' }}>
                                    Active
                                </label>
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

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Information User</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                {!! Form::label('id', 'ID:', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('id', $users1->id, ['class' => 'form-control','disabled' => 'disabled'] ) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('name', 'Full name:', ['class' => 'col-sm-2 control-label',]) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('name', $users1->fullname , ['class' => 'form-control','disabled' => 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('date', 'Date create:', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('date',date('d/m/Y H:i:s', strtotime($users1->created_at))  , ['class' => 'form-control','disabled' => 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('coin', 'Coins:', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('coin', $users1->coins , ['class' => 'form-control','disabled' => 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('point', 'Point:', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-5">
                                    {!! Form::text('point', $users1->point , ['class' => 'form-control','disabled' => 'disabled']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('point', 'Facebook:', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-5">
                                    <?php
                                    if ($users1->provider == 'facebook') {
                                        echo '<a class="btn btn-default" href="http://facebook.com/' . $users1->provider_id . '">link facebook</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="overflow-x: scroll;">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Information Transaction</h3>
                    </div>
                    <table class='table table-bordered table-hover table-striped'>
                        <thead>
                        <tr>
                            <th>Mã Giao Dịch</th>
                            <th>Mã Thẻ</th>
                            <th>Seri Thẻ</th>
                            <th>Loại</th>
                            <th>Trạng thái</th>
                            <th>Số tiền (VND)</th>
                            <th>Số Coin đã nạp</th>
                            <th>Ngày tháng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($log as $row)
                            <tr>
                                <td>{{$row->trans_id}}</td>
                                <td>{{$row->card_code}}</td>
                                <td>{{$row->card_seri}}</td>
                                <td>{{$row->card_type}}</td>
                                <td>{{$row->payment_status}}</td>
                                <td>{{number_format($row->amount, 0, '.', ',')}}</td>
                                <td>{{$row->coin}}</td>
                                <td>{{date('d/m/Y H:i:s', strtotime($row->created_at))}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                
                </div>
            </div>
        </div>
@endsection