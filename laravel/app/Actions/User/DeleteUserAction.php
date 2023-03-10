<?php

namespace App\Actions\User;

use App\Repository\AuthKeys\AuthKeysRepository;
use App\Repository\User\UserRepository;
use \Exception;

class DeleteUserAction
{
    protected UserRepository $userRepository;
    protected AuthKeysRepository $authKeysRepository;

    public function __construct(
        UserRepository $userRepository,
        AuthKeysRepository $authKeysRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->authKeysRepository = $authKeysRepository;
    }

    /**
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function handle(int $userId): string
    {
        $delete = $this->userRepository->deleteUser($userId);
        if(!$delete) throw new Exception('При удаления пользователя произошла ошибка');

        $this->authKeysRepository->deleteAllUserTokens($userId);

        return 'Пользователь удалён';
    }
}
