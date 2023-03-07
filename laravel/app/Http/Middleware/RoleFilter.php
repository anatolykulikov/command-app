<?php

namespace App\Http\Middleware;

use App\Http\Responses\Unauthorized;
use Closure;

class RoleFilter
{
    public function handle($request, Closure $next, $checkedRole)
    {
        if(!$request->user() || $request->user()->getRole() !== $checkedRole) return new Unauthorized();
        return $next($request);
    }
}
