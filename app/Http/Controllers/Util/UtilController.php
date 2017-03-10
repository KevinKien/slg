<?php

namespace App\Http\Controllers\Util;

use DB;
use Cache;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Redis\RedisServiceProvider;

class UtilController extends Controller {

    public function __construct() {
        
    }

    public function testdb() {
        if (DB::connection()->getDatabaseName()) {
            echo "conncted sucessfully to database " . DB::connection()->getDatabaseName();
        }
    }

    public function testmem() {
        $key = 'testmem';
        $value = 'ke ke ke ke';

        $time_live = Carbon::now()->addMinutes(10);

        if (!Cache::has($key)) {
            Cache::put($key, $value, $time_live);
        } else {
            $test_mem_val = Cache::get($key);
            echo 'set ' . $test_mem_val;
        }
    }

    public function testredis() {
        $key = 'testredis';
        $value = 'kakaka';

        $test_redis_val = Redis::get($key);
        if (!empty($test_redis_val)) {
            echo $test_redis_val;
        } else {
            //Redis::set($key, $value);
        }
    }
    

}
