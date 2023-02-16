<?php

namespace App\Actions\Auth;

use App\Actions\Auth\DTO\CreateNewToken;
use App\Helpers\UserHelper;
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

    public function __construct(
        UserRepository $userRepository,
        CreateNewToken $newToken,
        AuthKeysRepository $keysRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->newToken = $newToken;
        $this->keysRepository = $keysRepository;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(Request $request): bool
    {
        $validation = Validator::make($request->all(), [
            'lg' => 'required|string',
            'ps' => 'required|string',
        ]);
        if($validation->failed()) throw new Exception('Неправильные данные');

        $user = $this->userRepository->getUserByLogin($request->get('lg'));
        if(!$user) throw new Exception('Пользователя не существует');

        if(!UserHelper::comparePasswords($request->get('ps'), $user->getPassword())) {
            throw new Exception('Неправильный пароль');
        }

        $this->keysRepository->create(
            $user->getId(),
            $this->newToken
        );

        UserHelper::setAuthCookie($this->newToken);

        return true;
    }
}
