<?php

namespace App\Actions\Auth;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use Exception;
use Illuminate\Http\Request;

class CurrentLogoutAction
{
    protected AuthKeysRepository $keysRepository;

    public function __construct(
        AuthKeysRepository $keysRepository
    )
    {
        $this->keysRepository = $keysRepository;
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request, User $user): bool
    {
        $token = UserHelper::readAuthCookie($request);
        $delete = $this->keysRepository->deleteUserToken($user->getId(), $token->getKey());
        if(!$delete) throw new Exception('Произошла ошибка при выходе из системы');

        UserHelper::deleteAuthCookie();
        return true;
    }
}
