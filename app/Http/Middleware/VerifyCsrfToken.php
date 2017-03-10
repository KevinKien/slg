<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'onepiece/FBcallback',
        'oauth/register',
        'oauth/login',
        'oauth/authorize',
    ];
    
     public function handle($request, Closure $next)
    {
        if ($this->isReading($request) || $this->excludedRoutes($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        return redirect()->back()->withErrors(['message' => 'Yêu cầu không hợp lệ, vui lòng thử lại.']);
    }

    /**
     * Ignore CSRF on these routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function excludedRoutes($request)
    {
        $routes = [
          '/facebook/canvas',
          // ... insert all your canvas endpoints here
        ];

        foreach($routes as $route){
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }
}
