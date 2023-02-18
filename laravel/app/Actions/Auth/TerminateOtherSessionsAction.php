<?php

namespace App\Actions\Auth;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use Exception;
use Illuminate\Http\Request;

class TerminateOtherSessionsAction
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
        $delete = $this->keysRepository->deleteOtherUserTokens($user->getId(), $token->getKey());
        if($delete === false) throw new Exception('Произошла ошибка при завершении прочих сессий');
        if($delete === 0) throw new Exception('Нет сессий для удаления');
        return true;
    }
}
