<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class PublicMiddleware
{
    public function handle($request, Closure $next)
    {
        View::share('middlewareSharedData', 'This value was shared by our Middleware');

        return $next($request);
    }
}
