<?php

namespace App\Http\Middleware;

use App\Http\Responses\Forbidden;
use App\Http\Responses\Unauthorized;
use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if(!$request->user()) return new Unauthorized();
        if(!$request->user()->getActive() || $request->user()->isDeleted()) return new Forbidden();
        return $next($request);
    }
}
