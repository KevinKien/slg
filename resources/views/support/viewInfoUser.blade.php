@extends('app')

@section('htmlheader_title', 'Thông tin người dùng')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Thông tin người dùng</h3>
            </div><!-- /.box-header -->
            <!-- form start -->                               			
            <div class="box-body">
                <div class="form-group">							
                    <label class="col-sm-2 control-label">UserName: </label>
                    <div class="col-sm-5">								 
                        <label class="col-sm-2 control-label"><?php echo $user->name; ?></label>
                    </div>
                </div>                        						                                              
            </div><!-- /.box-body -->

            <div class="box-body">
                <div class="form-group">							
                    <label class="col-sm-2 control-label">Coins: </label>
                    <div class="col-sm-5">								 
                        <label class="col-sm-2 control-label"><?php echo $coin; ?></label>
                    </div>
                </div>                        						                                              
            </div><!-- /.box-body -->

            <div class="box-footer">
                <a href="javascript:history.back();" class="btn btn-default">Back</a>
                {!! Form::submit('Submit', ['class' => 'btn btn-info pull-right']) !!}
            </div><!-- /.box-footer -->                                                                                        
        </div>
    </div>
</div>

@if(isset($trans))
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Log Transactions Fail</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <table id="user-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th> 
                            <th>UserName</th>                                    
                            <th>Trans_id</th>
                            <th>Amount</th>
                            <th>Card_type</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0;?>
                        @foreach ($trans as $tran)
                        <?php $i++;?>
                        <tr>                        
                            <td>{{ $i }}</td>
                            <td>{{ $tran->name }}</td>
                            <td>{{ $tran->trans_id }}</td>
                            <td>{{ $tran->amount }}</td>
                            <td>{{ $tran->card_type }}</td>
                            <td>{{ $tran->payment_status }}</td>
                            <td>{{ $tran->created_at }}</td>    
                            <!--<td><a href='/compensation/editCoin?id={{$tran->id}}'><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</button></a></td>-->
                            <td><a href="{{ route('compensation.editCoin', $tran->id) }}"><button class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</button></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->

        </div>
    </div>
</div>
@endif
@endsection