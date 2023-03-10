<?php

namespace App\Http\Controllers;

use App\Actions\Auth\CurrentLogoutAction;
use App\Actions\Auth\FullLogoutAction;
use App\Actions\Auth\TerminateOtherSessionsAction;
use App\Actions\User\CreateNewUserAction;
use App\Actions\User\DTO\NewUserDTO;
use App\Actions\User\UpdateUserMetaAction;
use App\Actions\User\UserProfileAction;
use App\Helpers\UserHelper;
use App\Http\Responses\Success;
use App\Http\Responses\Error;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends AuthorizedController
{
    protected UserProfileAction $userProfileAction;
    protected CurrentLogoutAction $logoutAction;
    protected TerminateOtherSessionsAction $otherSessionsAction;
    protected FullLogoutAction $fullLogoutAction;
    protected UserHelper $userHelper;
    protected CreateNewUserAction $newUserAction;
    protected UpdateUserMetaAction $updateUserMetaAction;

    public function __construct(
        Request $request,
        UserProfileAction $userProfileAction,
        CurrentLogoutAction $logoutAction,
        TerminateOtherSessionsAction $otherSessionsAction,
        FullLogoutAction $fullLogoutAction,
        UserHelper $userHelper,
        CreateNewUserAction $newUserAction,
        UpdateUserMetaAction $updateUserMetaAction
    )
    {
        parent::__construct($request);
        $this->userProfileAction = $userProfileAction;
        $this->logoutAction = $logoutAction;
        $this->otherSessionsAction = $otherSessionsAction;
        $this->fullLogoutAction = $fullLogoutAction;
        $this->userHelper = $userHelper;
        $this->newUserAction = $newUserAction;
        $this->updateUserMetaAction = $updateUserMetaAction;
    }

    /**
     * Получение текущего профиля
     * @return Success
     */
    public function getCurrent(): Success
    {
        return new Success($this->userProfileAction->create($this->user));
    }

    /**
     * Обновление меты текущего пользователя
     * @return Success
     */
    public function updateMeta(): Success
    {
        return new Success(
            $this->updateUserMetaAction->handle(
                $this->request,
                $this->user
            )
        );
    }

    /**
     * Получить профиль другого пользователя
     * @param int $userId
     * @return Success|Error
     */
    public function getUser(int $userId): Success|Error
    {
        $requestedUser = User::getFromId($userId);
        if(!$requestedUser) return new Error('Пользователь не существует');
        return new Success($this->userProfileAction->create($requestedUser, false));
    }

    /**
     * Обновление меты для пользователя
     * @param int $userId
     * @return Success|Error
     */
    public function updateUserMeta(int $userId): Success|Error
    {
        $requestedUser = User::getFromId($userId);
        if(!$requestedUser) return new Error('Пользователь не существует');
        return new Success(
            $this->updateUserMetaAction->handle(
                $this->request,
                $requestedUser
            )
        );
    }

    /**
     * Выход из системы с завершением текущей сессии
     * @return Success|Error
     */
    public function logout(): Success|Error
    {
        try {
            $this->logoutAction->handle($this->request, $this->user);
            return new Success('Вы вышли из системы');
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }

    /**
     * Удаление токенов прочих сессий
     * @return Error|Success
     */
    public function terminateOtherSessions(): Error|Success
    {
        try {
            $this->otherSessionsAction->handle($this->request, $this->user);
            return new Success('Завершены все сессии, кроме текущей');
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }

    /**
     * Выход из системы с завершением всех сессии
     * @return Error|Success
     */
    public function fullLogout(): Error|Success
    {
        try {
            $this->fullLogoutAction->handle($this->user);
            return new Success('Вы вышли из системы, все сессии были завершены');
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }

    public function createUser(): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'login' => ['required', 'bail'],
                'password' => ['required', 'bail', Password::min(8)->mixedCase()->numbers()->symbols()],
                'active' => ['required', 'boolean'],
                'role' => ['required', 'bail'],
                'metas' => ['nullable', 'array'],
            ]);

            if (!$this->userHelper->assignRoleAbility(
                $this->user->getRole(),
                $validator->validated()['role'])
            ) {
                throw new Exception('Невозможно назначить роль');
            }

            return new Success($this->newUserAction->handle(
                new NewUserDTO($validator->validated()),
            ));


        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }
}
