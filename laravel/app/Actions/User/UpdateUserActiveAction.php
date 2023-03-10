<?php

namespace App\Actions\User;

use App\Repository\User\UserRepository;
use \Exception;

class UpdateUserActiveAction
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @param bool $nextState
     * @return string
     * @throws Exception
     */
    public function handle(int $userId, bool $nextState): string
    {
        if(!$this->userRepository->setAction($userId, $nextState)) {
            throw new Exception('При обновлении активности произошла ошибка');
        }

        return 'Пользователь ' . ($nextState ? 'активирован' : 'заблокирован');
    }
}
