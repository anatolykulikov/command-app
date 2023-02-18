<?php

namespace App\Actions\Auth;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use Exception;

class FullLogoutAction
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
    public function handle(User $user): bool
    {
        $delete = $this->keysRepository->deleteAllUserTokens($user->getId());
        if(!$delete) throw new Exception('Произошла ошибка при выходе из системы');

        UserHelper::deleteAuthCookie();
        return true;
    }
}
