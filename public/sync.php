<?php
set_time_limit(0);
error_reporting(E_ALL);

chdir('/');

echo '<pre>';
system('sh ./id.slg.vn 2>&1');
echo '</pre>';
?>