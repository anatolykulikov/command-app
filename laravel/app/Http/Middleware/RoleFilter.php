<?php

namespace App\Http\Middleware;

use App\Http\Responses\Forbidden;
use Closure;

class RoleFilter
{
    public function handle($request, Closure $next, $checkedRole)
    {
        if(!$request->user() || $request->user()->getRole() !== $checkedRole) return new Forbidden();
        return $next($request);
    }
}
