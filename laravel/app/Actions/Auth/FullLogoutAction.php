<?php

namespace App\Actions\Auth;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use Exception;

class FullLogoutAction
{
    protected AuthKeysRepository $keysRepository;
    protected UserHelper $userHelper;

    public function __construct(
        AuthKeysRepository $keysRepository,
        UserHelper $userHelper
    )
    {
        $this->keysRepository = $keysRepository;
        $this->userHelper = $userHelper;
    }

    /**
     * @throws Exception
     */
    public function handle(User $user): bool
    {
        $delete = $this->keysRepository->deleteAllUserTokens($user->getId());
        if(!$delete) throw new Exception('Произошла ошибка при выходе из системы');

        $this->userHelper->deleteAuthCookie();
        return true;
    }
}
