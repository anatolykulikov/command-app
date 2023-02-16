<?php

namespace App\Http\Middleware;

use App\Http\Responses\Unauthorized;
use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if(!$request->user()) return new Unauthorized();
        return $next($request);
    }
}
