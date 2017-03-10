<?php
namespace App\Helpers\Logs;

use Psr\Log\LogLevel;
use ReflectionClass;
use DateTime, DateInterval, DatePeriod;
use Predis\Client;

class LaravelLogHelper
{
    private static $key;
    private static $client;
    private static $prefix;

    private static $levels_classes = [
        'debug' => 'info',
        'info' => 'info',
        'notice' => 'info',
        'warning' => 'warning',
        'error' => 'danger',
        'critical' => 'danger',
        'alert' => 'danger',
        'emergency' => 'danger',
    ];

    private static $levels_imgs = [
        'debug' => 'info',
        'info' => 'info',
        'notice' => 'info',
        'warning' => 'warning',
        'error' => 'warning',
        'critical' => 'warning',
        'alert' => 'warning',
        'emergency' => 'warning',
    ];

    public static function setClient($config) {
        $client = [
            'scheme'   => 'tcp',
            'host'     => $config['connection']['host'],
            'port'     => $config['connection']['port'],
        ];

        if (isset($config['connection']['password']) && $config['connection']['password'] !== '')
        {
            $client['password'] = $config['connection']['password'];
        }

        if (isset($config['connection']['database']) && is_numeric($config['connection']['database']))
        {
            $client['database'] = intval($config['connection']['database']);
        }

        self::$prefix = $config['prefix'];
        self::$client = new Client($client);
    }

    /**
     * @param $key
     */
    public static function setKey($key)
    {
        self::$key = $key;
    }

    /**
     * @return string
     */
    public static function getKeyName()
    {
        return self::$key;
    }

    public static function allRedis()
    {
        $log = array();

        $log_levels = self::getLogLevels();

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';

        if (!self::$key) {
            $log_key = self::getKeys();
            if(!count($log_key)) {
                return [];
            }
            self::$key = $log_key[0];
        }

        $key = self::$key;

        $redis = self::$client;

        $length = $redis->lLen($key);

        if ($length === 0)
        {
            return null;
        }

        $cache = $redis->lRange($key, 0, $length - 1);

        $file = implode(PHP_EOL, $cache);

        preg_match_all($pattern, $file, $headings);

        if (!is_array($headings)) return $log;

        $log_data = preg_split($pattern, $file);

        if ($log_data[0] < 1) {
            array_shift($log_data);
        }

        foreach ($headings as $h) {
            for ($i=0, $j = count($h); $i < $j; $i++) {
                foreach ($log_levels as $level_key => $level_value) {
                    if (strpos(strtolower($h[$i]), '.' . $level_value)) {

                        preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?\.' . $level_key . ': (.*?)( in .*?:[0-9]+)?$/', $h[$i], $current);

                        if (!isset($current[2])) continue;

                        preg_match('/"server"\:"(.*?)"/', $log_data[$i], $_server);
                        preg_match('/"user_name"\:"(.*?)"/', $log_data[$i], $_user_name);
                        preg_match('/"user_id"\:"(.*?)"/', $log_data[$i], $_user_id);
                        preg_match('/"url"\:"(.*?)"/', $log_data[$i], $_url);

                        if (!isset($_server[1]))
                        {
                            preg_match('/"server"\:"(.*?)"/', $current[2], $_server);
                            preg_match('/"user_name"\:"(.*?)"/', $current[2], $_user_name);
                            preg_match('/"user_id"\:"(.*?)"/', $current[2], $_user_id);
                            preg_match('/"url"\:"(.*?)"/', $current[2], $_url);
                        }

                        $server = isset($_server[1]) ? $_server[1] : '';
                        $user_name = isset($_user_name[1]) ? $_user_name[1] : '';
                        $user_id = isset($_user_id[1]) ? $_user_id[1] : '';
                        $url = isset($_url[1]) ? $_url[1] : '';

                        $log[] = array(
                            'level' => $level_value,
                            'level_class' => self::$levels_classes[$level_value],
                            'level_img' => self::$levels_imgs[$level_value],
                            'date' => $current[1],
                            'text' => $current[2],
                            'server' => $server,
                            'user_name' => $user_name,
                            'user_id' => $user_id,
                            'url' => $url,
                            'in_file' => isset($current[3]) ? $current[3] : null,
                            'stack' => preg_replace("/^\n*/", '', $log_data[$i])
                        );
                    }
                }
            }
        }

        return array_reverse($log);
    }

    /**
     * @return array
     */
    public static function getKeys()
    {
        $today = date('Y-m-d');
        $begin = new DateTime($today);
        $end = new DateTime($today);
        $begin->modify('-1 month');
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $range = new DatePeriod($begin, $interval, $end);

        $keys = [];

        $redis = self::$client;

        foreach ($range as $date) {
            $key = self::$prefix . $date->format('d-m-Y');

            if ($redis->exists($key))
            {
                $keys[] = $key;
            }
        }

        return array_reverse($keys);
    }

    public static function deleteKey($key)
    {
        $redis = self::$client;
        $redis->del($key);
    }

    /**
     * @return array
     */
    private static function getLogLevels()
    {
        $class = new ReflectionClass(new LogLevel);
        return $class->getConstants();
    }
}
