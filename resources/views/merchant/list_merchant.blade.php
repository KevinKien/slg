@extends('app')

@section('htmlheader_title')
    Trang Danh sách thông tin Game
@endsection
@section('contentheader_title','Danh sách thông tin Game')
@section('main-content')

    <div class="row">
        <div class="col-md-3">
              <a class="btn btn-primary btn-block margin-bottom" href="/merchant_app/add">THÊM</a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Folders</h3>
                  <div class="box-tools">
                    <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#"><i class="fa fa-inbox"></i> Game <span class="label label-primary pull-right"><?php
                    $i1=0; 
                    foreach($results_total as $row1){
                        $i1=$i1+1;
                        
                    } print($i1); ?></span></a></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                    <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                    <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a></li>
                    <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Labels</h3>
                  <div class="box-tools">
                    <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                    <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                    <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
    </div>
    <div class="col-md-9">
        <div class="box box-primary">
    
            <div class="box-header with-border">
                <h3 class="box-title">List</h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input type="text" placeholder="Search Mail" class="form-control input-sm">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div>
            </div>
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">        
                    <table class='table table-hover table-striped'>
                      <thead>
                        <tr>
                            <th>STT</th>
                            <th></th>
                            <th></th>
                            <th>APPID</th>
                            <th>TÊN GAME</th>
                            <th>IMAGE</th>
                            
                            <th>STATUS</th>
                            
                            
                        </tr>
                      </thead>  
                    <tbody>
                   <?php
                   $i=0;
                   $out='';
                   
                   foreach($results as $row){
                    $i=$i+1;
                    if(isset($_GET['page'])){
                            $tt=$_GET['page']*10-10+$i;
                        }
                        if(!isset($_GET['page'])){
                          $tt=$i;  
                        }
                    $status=$row->status;
                    if($status==1){
                        $status='Public';
                    }elseif($status==0){
                        $status='Maintain';
                    }elseif ($status==2) {
                        $status="Not public";
                    }
                   $img = json_decode($row->images);

                    $out.="<tr>
                            <td>".$tt."</td>
                            <td><div class='icheckbox_flat-blue' style='position: relative;' aria-checked='false' aria-disabled='false'><input type='checkbox' style='position: absolute; opacity: 0;'><ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;'></ins></div></td>
                            <td class='mailbox-star'><a href='#'><i class='fa text-yellow fa-star'></i></a></td>
                            <td>".$row->id."</td>
                            <td><a href='/merchant_app/edit?appid=".$row->id."'>".$row->name."</a></td>
                            <td><img src='".$img->thumb."'style='width: 45px;height: 45px;'></td>
                            <td>".$status."</td>
                            
                            </tr>";
                   }
                   print $out;
                   ?>
        	       </tbody>
                </table>
                </div>
            </div>
            <div class="box-footer no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                </div>
            <div class="box-footer clearfix">
                <?php echo $results->render();  ?>
         </div>
    </div>
    </div>

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