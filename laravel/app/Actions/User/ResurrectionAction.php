<?php

namespace App\Actions\User;

use App\Repository\User\UserRepository;
use \Exception;

class ResurrectionAction
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function handle(int $userId): string
    {
        if(!$this->userRepository->recover($userId)) {
            throw new Exception('При восстановлении пользователя произошла ошибка');
        }
        return 'Пользователь восстановлен';
    }
}
