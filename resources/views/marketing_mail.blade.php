@extends('app')

@section('htmlheader_title', 'Gửi mail đến người dùng')

@section('css-current')
    <script src="//id.slg.vn/plugins/ckeditorfull/ckeditor.js"></script>
@endsection

@section('main-content')
    <div class="row">
        {!! Form::open([
              'route' => 'marketingmail.insert',

                  'method' => 'post'
               ]) !!}
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Compose New Message</h3>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <input class="form-control" name="text_subject" id="text_subject" placeholder="Subject:">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-1 control-label">Game :</label>
                        <div class="col-sm-3">
                            <select id='game' class='form-control' name='game' style="margin-top: -8px ; margin-left: -10px;">
                                <?php
                                $option = '';
                                $option.="<option value='1'>Tất cả</option>";
                                foreach($listgame as $acc){
                                    $option.="<option value='$acc->id'>".$acc->name."</option>";
                                }
                                print $option;
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <br/>
                    </div>
                    <div class="form-group">
                        <textarea name="text_content" id="text_content"></textarea>
                        <script>
                            CKEDITOR.replace( 'text_content' );
                        </script>
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">

                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>

                    </div>
                </div>

                <!-- /.box-footer -->
            </div>
            <!-- /. box -->
        </div>
        {!! Form::close() !!}
    </div>

@endsection