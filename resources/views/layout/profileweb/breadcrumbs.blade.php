<?php $sl_clss = isset($selectbar)?$selectbar:''; ?>
<article class="subMenu">
    <h1>Hồ sơ cá nhân</h1>
    <menu>
        <li><a class="<?php if($sl_clss =='profile') echo 'on'; ?>"  href="/profile" rel="">Thông tin đăng nhập</a></li>
        <li><a class="<?php if($sl_clss =='personal') echo 'on'; ?>"  href="/profile/personal" rel="">Thông tin chung</a></li>
    </menu>
</article>