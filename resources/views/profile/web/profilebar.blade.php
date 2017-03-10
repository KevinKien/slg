<div class="box  box-info profile_bar">
    <?php $sl_clss = isset($selectbar)?$selectbar:''; ?>
    <div class="box-header with-border">
        <h3 class="box-title">Profile </h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <ul class="profile_menu">

            <li >
                <a href="/profile">
                    <div class="info-box">
                        <div class="info-box-content <?php if($sl_clss =='profile') echo 'barselect'; ?>">
                            <h3 class="info-box-text">Thông tin đăng nhập</h3>
                            <span class="progress-description">Quản lý thông tin dùng để đăng nhập</span>
                        </div><!-- /.info-box-content -->
                    </div>
                </a>
            </li>
            <li >
                <a href="/profile/personal">
                    <div class="info-box">
                        <div class="info-box-content <?php if($sl_clss =='personal') echo 'barselect'; ?>">
                            <h3 class="info-box-text">Thông tin chung</h3>
                            <span class="progress-description">Xem và cập nhật thông tin cá nhân</span>
                        </div><!-- /.info-box-content -->
                    </div>
                </a>
            </li>
        </ul>

    </div>
</div>