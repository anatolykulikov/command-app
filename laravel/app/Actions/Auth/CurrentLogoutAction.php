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
    public function handle(Request $request, User $user): bool
    {
        $token = $this->userHelper->readAuthCookie($request);
        $delete = $this->keysRepository->deleteUserToken($user->getId(), $token->getKey());
        if(!$delete) throw new Exception('Произошла ошибка при выходе из системы');

        $this->userHelper->deleteAuthCookie();
        return true;
    }
}
