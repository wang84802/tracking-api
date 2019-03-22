<?php

namespace App\Http\Middleware;

use Closure;

class AccessMiddleware {
    protected $allowed_origins = [
        'http://beta-track-view.contin-testing-site.com',
        'https://uat-track-view.contin-testing-site.com'
    ];
    public function handle($request, Closure $next) {
        $http_origin = $request->header('origin');

        if (in_array($http_origin, $this->allowed_origins))
                return $next($request)->header('Access-Control-Allow-Origin', $http_origin);
        else
            return 'no';
    }
}
