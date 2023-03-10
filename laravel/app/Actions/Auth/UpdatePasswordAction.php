<?php

namespace App\Actions\Auth;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use App\Repository\User\UserRepository;
use \Exception;
use Illuminate\Http\Request;

class UpdatePasswordAction
{
    protected UserRepository $userRepository;
    protected UserHelper $userHelper;
    protected AuthKeysRepository $keysRepository;

    public function __construct(
        UserRepository $userRepository,
        UserHelper $userHelper,
        AuthKeysRepository $keysRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userHelper = $userHelper;
        $this->keysRepository = $keysRepository;
    }

    /**
     * @param Request $request
     * @param int $userId
     * @param string $oldPassword
     * @param string $nextPassword
     * @return string
     * @throws Exception
     */
    public function handle(
        Request $request,
        int $userId,
        string $oldPassword,
        string $nextPassword,
        bool $isCurrent = true
    ): string
    {
        // Проверяем указанный пароль с указанным в базе
        if(!$this->userHelper->comparePasswords(
            $oldPassword,
            $this->userRepository->getPassword($userId)
        )) {
            throw new Exception('Подтвердить смену пароля не получилось');
        }

        // Обновление пароля
        if(!$this->userRepository->updatePassword(
            $userId,
            $this->userHelper->hashPassword($nextPassword)
        )) {
            throw new Exception('При установке нового пароля произошла ошибка');
        }

        // Удаляем старые токены
        if(!$isCurrent) $this->keysRepository->deleteAllUserTokens($userId);
        if($isCurrent) {
            $token = $this->userHelper->readAuthCookie($request);
            $this->keysRepository->deleteOtherUserTokens($userId, $token->getKey());
        }

        return 'Пароль пользователя успешно обновлён';
    }
}
