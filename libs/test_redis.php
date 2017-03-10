<?php

$options = array(
    'namespace' => 'Application_',
    'servers' => array(
        array('host' => '192.168.1.194', 'port' => 6379),
    )
);

require_once 'Rediska.php';
$rediska = new Rediska($options);

// Set 'value' to key 'keyName'
$key = new Rediska_Key('keyName');
$key->setValue('value');

echo $key->getValue();

?>