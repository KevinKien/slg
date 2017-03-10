<div class="box-header with-border">
                        <h3 class="box-title">Select option</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <form role="form" method="get" name="" id="logniu">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">               
                            <div class="form-group">
                                <label>Select App</label>
                                <?php
                                $app_id_choice =array();
                                if(isset($_GET['app_id'])){
                                    $app_id_choice = $_GET['app_id'];
                                }
                                if(Auth::user()->is('deploy')){
                                ?>
                                @foreach ($list_option['appid'] as $appid)
                                <p>{{$appid->name}}</p>
                                @endforeach
                                <?php
                                }else{
                                ?>
                                <select class="form-control" name="app_id[]" id="select_app" required >
                                    @foreach ($list_option['appid'] as $appid)
                                    <option value="{{$appid->app_id}}" <?php if(in_array($appid->app_id, $app_id_choice)) echo "selected";  ?> >{{$appid->name}}</option>
                                    @endforeach
                                </select>
                                <?php 
                                } 
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Select parner</label>
                                @if($list_option['partner'])
                                @foreach ($list_option['partner'] as $partner)
                                <div class="checkbox">
                                    <label>
                                        <?php 
                                        if(isset($_GET['partner_id'])){
                                            $partner_id_choice = $_GET['partner_id'];
                                        }
                                        
                                        ?>
                                        @if(isset($_GET['partner_id']))
                                        <input class="partner_id" name="partner_id[]" type="checkbox" value = "{{$partner->partner_id}}" <?php if(in_array($partner->partner_id, $partner_id_choice)) echo 'checked=""';  ?>   <?php if($user_type == 'partner'){?>disabled <?php } ?> >{{$partner->partner_name}}
                                        @else
                                        <input class="partner_id" name="partner_id[]" type="checkbox" value = "{{$partner->partner_id}}" checked=""  <?php if($user_type == 'partner'){?>disabled <?php } ?> >{{$partner->partner_name}}
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                                @endif

                            </div>
<!--                            <div class="form-group">
                                <label>Select App</label>
                                
                               
                                @if($list_option['appid'])
                                @foreach ($list_option['appid'] as $appid)
                                <div class="checkbox">
                                    <label>
                                        @if(isset($_GET['app_id']))
                                        <input class="app_id" name="app_id[]" type="checkbox" value = "{{$appid->app_id}}" <?php if(in_array($appid->app_id, $app_id_choice)){ ?>checked="" <?php } if($user_type == 'deploy'){?>disabled <?php } ?> >{{$appid->name}}
                                        @else
                                        <input class="app_id" name="app_id[]" type="checkbox" value = "{{$appid->app_id}}" checked="" <?php if($user_type == 'deploy'){?>disabled <?php } ?> >{{$appid->name}}
                                        @endif
                                        
                                    </label>
                                </div>
                                @endforeach
                                @endif
                                

                            </div>-->
<!--                            <div class="form-group">
                                <label>Select App</label>
                                <select class="form-control select2 app_id" multiple="multiple" name="app_id[]" data-placeholder="Select a State" style="width: 100%;">
                                    @foreach ($list_option['appid'] as $appid)
                                    <option value="{{$appid->app_id}}" <?php if(in_array($appid->app_id, $app_id_choice)) echo "selected";  ?> >{{$appid->name}}</option>
                                    @endforeach
                                </select>
                            </div> /.form-group -->
                            <div class="form-group">
                                <label>Select Cp</label>
                                
                                <?php
                                $cp_id_choice =array();
                                if(isset($_GET['cp_id'])){
                                    $cp_id_choice = $_GET['cp_id'];
                                }
                                ?>
                                <select id="select_cp" class="form-control select2 app_id" multiple="multiple" name="cp_id[]" data-placeholder="Select cpid" style="width: 100%;" required>
                                    @foreach ($list_option['cpid'] as $cpid)
                                    <?php if($cp_id_choice){ ?>
                                    <option value="{{$cpid->cpid}}" <?php if(in_array($cpid->cpid, $cp_id_choice)) echo "selected";  ?> alt="{{$cpid->partner_id}}" >{{$cpid->cp_name}}</option>
                                    <?php }else{ ?>
                                    <option value="{{$cpid->cpid}}" selected alt="{{$cpid->partner_id}}" rel="{{$cpid->app_id}}" >{{$cpid->cp_name}}</option>
                                    <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date from:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="date-from" name = "date-from" required value="<?php echo isset($_GET['date-from'])?$_GET['date-from']:date("d-m-Y", strtotime("-7 day", time())); ?>" />
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                            <div class="form-group">
                                <label>Date to:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="date-to" name="date-to" required value="<?php echo isset($_GET['date-to'])?$_GET['date-to']:date("d-m-Y", strtotime("-0 day", time())); ?>"/>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary submit form_submit" >Submit</button>
                            </div>
                        </form>
                    </div><!-- /.box-body -->