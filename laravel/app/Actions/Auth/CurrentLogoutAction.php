<?php

namespace App\Actions\Auth;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use Illuminate\Http\Request;

class CurrentLogoutAction
{
    protected  $keysRepository;

    public function __construct(
        AuthKeysRepository $keysRepository
    )
    {
        $this->keysRepository = $keysRepository;
    }

    public function handle(Request $request, User $user): bool
    {
        $token = UserHelper::readAuthCookie($request);
        $delete = $this->keysRepository->deleteUserToken($user->getId(), $token->getKey());
        if(!$delete) return false;

        UserHelper::deleteAuthCookie();
        return true;
    }
}
