<?php

namespace App\Actions\User;

use App\Actions\User\DTO\NewUserDTO;
use App\Models\User;
use App\Repository\User\UserMetaRepository;
use App\Repository\User\UserRepository;
use Exception;

class CreateNewUserAction
{
    protected UserRepository $userRepository;
    protected UserMetaRepository $userMetaRepository;
    protected UserProfileAction $userProfileAction;

    public function __construct(
        UserRepository $userRepository,
        UserMetaRepository $userMetaRepository,
        UserProfileAction $userProfileAction
    )
    {
        $this->userRepository = $userRepository;
        $this->userMetaRepository = $userMetaRepository;
        $this->userProfileAction = $userProfileAction;
    }

    /**
     * @param NewUserDTO $user
     * @return UserProfileAction
     * @throws Exception
     */
    public function handle(NewUserDTO $user): UserProfileAction
    {
        if($this->userRepository->getUserByLogin($user->getLogin())) {
            throw new Exception('Пользователь с таким именем уже существует');
        }

        $user->setId($this->userRepository->createUser($user));
        if(!$user->getId()) throw new Exception('При создании пользователя произошла ошибка');

        $this->userMetaRepository->createUserMeta(
            $user->getId(),
            $user->getMetas()
        );

        $requestedUser = User::getFromId($user->getId());
        return $this->userProfileAction->create($requestedUser, false);
    }
}
