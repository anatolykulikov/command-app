<?php

namespace App\Providers;

use App\Helpers\UserHelper;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $auth = $this->app['auth'];
        $auth->viaRequest('web', [$this, 'getUser']);
    }

    public function getUser(Request $request): ?User
    {
        $token = (new UserHelper)->readAuthCookie($request);
        if(!$token) return null;
        return User::getFromToken($token->getKey());
    }
}
