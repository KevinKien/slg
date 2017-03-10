<?php

namespace App\Http\Middleware;

use Closure, Memcached, Cache, CommonHelper;

class LogRequest
{
    private function getMilliseconds()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cache::get('settings_request_log') == 1) {
            $timeStart = $this->getMilliseconds();

            $response = $next($request);

            $time = $this->getMilliseconds() - $timeStart;

            $payload = [
                'url' => $request->url(),
                'ip' => json_encode(['server' => $request->server('SERVER_ADDR'), 'client' => CommonHelper::getClientIP()]),
                'method' => $request->method(),
                'payload' => json_encode($request->all()),
                'response_time' => $time,
                'response_content' => $response->content(),
                'created_at' => time(),
            ];

            try {
                $m = new Memcached();

                $config = config('cache.log-request');
                $m->addServer($config['host'], $config['port']);

                $m->set('slg_log_request_' . microtime(true), $payload, time() + 1296000); //15 days
            } catch (Exception $ex) {

            }
        } else {
            $response = $next($request);
        }

        return $response;
    }
}
