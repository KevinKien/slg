@extends('app')

@section('htmlheader_title', 'Thay đổi thông tin Giao dịch')

@section('main-content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Bù coin giao dịch</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            {!! Form::open([
            'route' => 'compensation.update',
            'class' => 'form-horizontal',
            'method' => 'POST'
            ]) !!}
            <input type="hidden" name="trans_id" value="<?php echo $trans_id; ?>"/>
            <input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
            <input type="hidden" name="username" value="<?php echo $username; ?>"/>
            <input type="hidden" name="status" value="success"/>
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">UserName</label>                                                
                    <div class="col-sm-5">
                        <label class="col-sm-2 control-label"><?php echo $username; ?></label>

                    </div>
                </div>
            </div><!-- /.box-body --> 
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Coins</label>                                                
                    <div class="col-sm-5">
                        <label class="col-sm-2 control-label"><?php echo $coins; ?></label>

                    </div>
                </div>
            </div><!-- /.box-body --> 
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Mã giao dịch</label>                                                
                    <div class="col-sm-5">
                        <label class="col-sm-2 control-label"><?php echo $trans_id; ?></label>

                    </div>
                </div>
            </div><!-- /.box-body -->
            <!--  <div class="box-body">
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Trạng thái</label>                                                
                      <div class="col-sm-5">                                                    
                          <select name="status">
                              <option value="fail">That bai</option>
                              <option value="success">Thanh Cong</option>                                                        
                          </select>
                      </div>
                  </div>
              </div> --> <!-- /.box-body -->
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Số Coins:</label>                                                
                    <div class="col-sm-5">                                                    
                        <input type="text" name="txtCoin" class="form-control" maxlength="8"/>                                                  
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
@endsection