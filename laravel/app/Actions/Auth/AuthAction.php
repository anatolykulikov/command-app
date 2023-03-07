<?php

namespace App\Actions\Auth;

use App\Actions\Auth\DTO\CreateNewToken;
use App\Actions\User\UserProfileAction;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Repository\AuthKeys\AuthKeysRepository;
use App\Repository\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthAction
{
    private UserRepository $userRepository;
    private CreateNewToken $newToken;
    private AuthKeysRepository $keysRepository;
    private UserProfileAction $userProfileAction;
    protected UserHelper $userHelper;

    public function __construct(
        UserRepository $userRepository,
        CreateNewToken $newToken,
        AuthKeysRepository $keysRepository,
        UserProfileAction $userProfileAction,
        UserHelper $userHelper
    )
    {
        $this->userRepository = $userRepository;
        $this->newToken = $newToken;
        $this->keysRepository = $keysRepository;
        $this->userProfileAction = $userProfileAction;
        $this->userHelper = $userHelper;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(Request $request): UserProfileAction
    {
        $validation = Validator::make($request->all(), [
            'lg' => 'required|string',
            'ps' => 'required|string',
        ]);
        if($validation->failed()) throw new Exception('Неправильные данные');

        $user = $this->userRepository->getUserByLogin($request->get('lg'));
        if(!$user) throw new Exception('Пользователя не существует');

        if(!$this->userHelper->comparePasswords($request->get('ps'), $user->getPassword())) {
            throw new Exception('Неправильный пароль');
        }

        $this->keysRepository->create(
            $user->getId(),
            $this->newToken
        );

        $this->userHelper->setAuthCookie($this->newToken);

        return $this->userProfileAction->create(
            User::getFromId($user->getId())
        );
    }
}
