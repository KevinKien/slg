<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Log\Writer;
use Monolog\Handler\RedisHandler;
use Predis\Client;
use Monolog\Formatter\LineFormatter;
use Auth;

class RedisLoggerServiceProvider extends ServiceProvider
{
    /**
     * Push the redis handler to monolog on boot.
     */
    public function boot()
    {
        $config = config('redis-logger');

        if (isset($config['enabled']) && $config['enabled'] === true && isset($config['connection'])) {
            $connection = $config['connection'];

            $logger = app('log');

            // Make sure the logger is a Writer instance
            if ($logger instanceof Writer && isset($connection['host']) && isset($connection['port'])) {

                $key = date('d-m-Y');

                if (isset($config['prefix']) && $config['prefix'] !== '') {
                    $key = $config['prefix'] . $key;
                }

                $client = [
                    'scheme' => 'tcp',
                    'host' => $connection['host'],
                    'port' => $connection['port'],
                ];

                if (isset($connection['password']) && $connection['password'] !== '') {
                    $client['password'] = $connection['password'];
                }

                if (isset($connection['database']) && is_numeric($connection['database'])) {
                    $client['database'] = intval($connection['database']);
                }

                $redis = new Client($client);

                $day = (isset($config['ttl']) && intval($config['ttl']) > 0) ? intval($config['ttl']) : 30;

                $redis->expireat($key, strtotime('+' . $day . ' day'));

                $handler = new RedisHandler($redis, $key);

                $handler->setFormatter(new LineFormatter(null, null, true, true));

                $logger->getMonolog()->pushHandler($handler);

                $logger->getMonolog()->pushProcessor(function ($record) {
                    if (isset($_SERVER['SERVER_ADDR'])) {
                        $record['extra']['server'] = $_SERVER['SERVER_ADDR'];
                    }

                    if (isset($_SERVER['HTTP_USER_AGENT'])) {
                        $record['extra']['useragent'] = $_SERVER['HTTP_USER_AGENT'];
                    }

                    if (isset($_SERVER['SERVER_PORT']) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                        $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                        $record['extra']['url'] = $url;
                    }

                    if (Auth::check()) {
                        $record['extra']['user_name'] = Auth::user()->name;
                        $record['extra']['user_id'] = (string)Auth::id();
                    }

                    return $record;
                });
            }
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {

    }
}