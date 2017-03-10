<?php
return array (
  'stats_api' => 'Server',
  'slabs_api' => 'Server',
  'items_api' => 'Server',
  'get_api' => 'Memcache',
  'set_api' => 'Memcache',
  'delete_api' => 'Server',
  'flush_all_api' => 'Server',
  'connection_timeout' => '1',
  'max_item_dump' => '100',
  'refresh_rate' => 5,
  'memory_alert' => '80',
  'hit_rate_alert' => '90',
  'eviction_alert' => '0',
  'file_path' => 'Temp/',
  'servers' => 
  array (
    'Default' => 
    array (
      '192.168.1.219:11211' => 
      array (
        'hostname' => '192.168.1.219',
        'port' => '11211',
      ),
      '192.168.1.219:11212' => 
      array (
        'hostname' => '192.168.1.219',
        'port' => '11212',
      ),
    ),
  ),
);