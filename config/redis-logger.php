<?php

return [
	'enabled' => false,
	'prefix' => 'logs-',
	'ttl' => 7, //days
	'connection' => [
        'name' => 'logs',
        'host' => '127.0.0.1', //192.168.1.252
        'port' => 6379,
        'password' => '', //Dh1BSeqMz6iKPOSfVgx1
        'database' => 0,
    ]
];