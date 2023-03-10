<?php

namespace App\Http\Controllers;

use App\Actions\Auth\CurrentLogoutAction;
use App\Actions\Auth\FullLogoutAction;
use App\Actions\Auth\TerminateOtherSessionsAction;
use App\Actions\Auth\UpdatePasswordAction;
use App\Actions\User\CreateNewUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\DTO\NewUserDTO;
use App\Actions\User\ResurrectionAction;
use App\Actions\User\UpdateUserActiveAction;
use App\Actions\User\UpdateUserMetaAction;
use App\Actions\User\UserProfileAction;
use App\Helpers\UserHelper;
use App\Http\Responses\Success;
use App\Http\Responses\Error;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends AuthorizedController
{
    /**
     * Получение текущего профиля
     * @param UserProfileAction $userProfileAction
     * @return Success|Error
     */
    public function getCurrent(UserProfileAction $userProfileAction): Success|Error
    {
        try {
            return new Success($userProfileAction->create($this->user));
        } catch (Exception $exception) {
            return new Error('При получении профиля произошла ошибка');
        }
    }


    /**
     * Обновление меты текущего пользователя
     * @param UpdateUserMetaAction $updateUserMetaAction
     * @return Success|Error
     */
    public function updateMeta(UpdateUserMetaAction $updateUserMetaAction): Success|Error
    {
        try {
            return new Success(
                $updateUserMetaAction->handle(
                    $this->request,
                    $this->user
                )
            );
        } catch (Exception $exception) {
            return new Error('При обновлении данных произошла ошибка');
        }
    }


    /**
     * Обновить пароль текущему пользователю
     * @param UpdatePasswordAction $updatePasswordAction
     * @return Success|Error
     */
    public function updatePassword(UpdatePasswordAction $updatePasswordAction): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'oldPassword' => ['required', 'bail', 'string'],
                'nextPassword' => ['required', 'bail', Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);

            if($validator->fails()) {
                if(!empty($validator->messages()->get('oldPassword'))) {
                    throw new Exception('Необходимо указать текущий пароль');
                }
                if(!empty($validator->messages()->get('nextPassword'))) {
                    throw new Exception('Новый пароль не подходит');
                }
            }

            return new Success($updatePasswordAction->handle(
                $this->request,
                $this->user->getId(),
                $validator->validated()['oldPassword'],
                $validator->validated()['nextPassword']
            ));

        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Обновить пароль другому пользователю
     * @param int $userId
     * @param UserHelper $userHelper
     * @param UpdatePasswordAction $updatePasswordAction
     * @return Success|Error
     */
    public function updateUserPassword(
        int $userId,
        UserHelper $userHelper,
        UpdatePasswordAction $updatePasswordAction
    ): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'oldPassword' => ['required', 'bail', 'string'],
                'nextPassword' => ['required', 'bail', Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);

            if($validator->fails()) {
                if(!empty($validator->messages()->get('oldPassword'))) {
                    throw new Exception('Необходимо указать текущий пароль');
                }
                if(!empty($validator->messages()->get('nextPassword'))) {
                    throw new Exception('Новый пароль не подходит');
                }
            }

            $requestedUser = User::getFromId($userId);
            if(!$requestedUser) return new Error('Пользователь не существует');

            if (!$userHelper->assignRoleAbility(
                $this->user->getRole(),
                $requestedUser->getRole())
            ) {
                throw new Exception('Невозможно изменить активность пользователя');
            }

            return new Success($updatePasswordAction->handle(
                $this->request,
                $requestedUser->getId(),
                $validator->validated()['oldPassword'],
                $validator->validated()['nextPassword'],
                false
            ));

        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Получить профиль другого пользователя
     * @param int $userId
     * @param UserProfileAction $userProfileAction
     * @return Success|Error
     */
    public function getUser(
        int $userId,
        UserProfileAction $userProfileAction
    ): Success|Error
    {
        try {
            $requestedUser = User::getFromId($userId);
            if(!$requestedUser) return new Error('Пользователь не существует');
            return new Success($userProfileAction->create($requestedUser, false));

        } catch (Exception $exception) {
            return new Error('При получения профиля пользователя произошла ошибка');
        }
    }


    /**
     * Обновление меты для пользователя
     * @param int $userId
     * @param UpdateUserMetaAction $updateUserMetaAction
     * @return Success|Error
     */
    public function updateUserMeta(
        int $userId,
        UpdateUserMetaAction $updateUserMetaAction
    ): Success|Error
    {
        try {
            $requestedUser = User::getFromId($userId);
            if(!$requestedUser) return new Error('Пользователь не существует');

            return new Success(
                $updateUserMetaAction->handle(
                    $this->request,
                    $requestedUser
                )
            );

        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Выход из системы с завершением текущей сессии
     * @param CurrentLogoutAction $logoutAction
     * @return Success|Error
     */
    public function logout(CurrentLogoutAction $logoutAction): Success|Error
    {
        try {
            $logoutAction->handle($this->request, $this->user);
            return new Success('Вы вышли из системы');
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Удаление токенов прочих сессий
     * @param TerminateOtherSessionsAction $otherSessionsAction
     * @return Success|Error
     */
    public function terminateOtherSessions(TerminateOtherSessionsAction $otherSessionsAction): Success|Error
    {
        try {
            $otherSessionsAction->handle($this->request, $this->user);
            return new Success('Завершены все сессии, кроме текущей');
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Выход из системы с завершением всех сессии
     * @param FullLogoutAction $fullLogoutAction
     * @return Success|Error
     */
    public function fullLogout(FullLogoutAction $fullLogoutAction): Success|Error
    {
        try {
            $fullLogoutAction->handle($this->user);
            return new Success('Вы вышли из системы, все сессии были завершены');
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Список ролей пользователей
     * @param UserHelper $userHelper
     * @return Success|Error
     */
    public function getUserRoles(UserHelper $userHelper): Success|Error
    {
        try {
            return new Success(
                $userHelper->getUserRoleList(
                    $this->user->getRole()
                )
            );
        } catch (Exception $exception) {
            return new Error('При получении списка ролей пользователей произошла ошибка');
        }
    }


    /**
     * Создание пользователя
     * @param CreateNewUserAction $newUserAction
     * @param UserHelper $userHelper
     * @return Success|Error
     */
    public function createUser(
        CreateNewUserAction $newUserAction,
        UserHelper $userHelper
    ): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'login' => ['required', 'bail'],
                'password' => ['required', 'bail', Password::min(8)->mixedCase()->numbers()->symbols()],
                'active' => ['required', 'boolean'],
                'role' => ['required', 'bail'],
                'metas' => ['nullable', 'array'],
            ]);

            if($validator->fails()) {
                throw new Exception('Не переданы нужные параметры для создания пользователя');
            }

            if (!$userHelper->assignRoleAbility(
                $this->user->getRole(),
                $validator->validated()['role'])
            ) {
                throw new Exception('Невозможно назначить роль');
            }

            return new Success($newUserAction->handle(
                new NewUserDTO($validator->validated()),
            ));


        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Установить пользователю активность (разблокировать / заблокировать)
     * @param UpdateUserActiveAction $updateUserActiveAction
     * @param UserHelper $userHelper
     * @return Success|Error
     */
    public function setUserAction(
        UpdateUserActiveAction $updateUserActiveAction,
        UserHelper $userHelper
    ): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'userId' => ['required', 'bail', 'int'],
                'action' => ['required', 'bail', 'bool'],
            ]);

            if($validator->fails()) throw new Exception('Не переданы параметры');

            $requestedUser = User::getFromId($validator->validated()['userId']);
            if(!$requestedUser) return new Error('Пользователь не существует');

            if (!$userHelper->assignRoleAbility(
                $this->user->getRole(),
                $requestedUser->getRole())
            ) {
                throw new Exception('Невозможно изменить активность пользователя');
            }

            return new Success(
                $updateUserActiveAction->handle(
                    $requestedUser->getId(),
                    $validator->validated()['action']
                )
            );

        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Софт-удаление пользователя
     * @param DeleteUserAction $deleteUserAction
     * @param UserHelper $userHelper
     * @return Success|Error
     */
    public function deleteUser(
        DeleteUserAction $deleteUserAction,
        UserHelper $userHelper
    ): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'userId' => ['required', 'bail', 'int'],
            ]);

            if($validator->fails()) throw new Exception('Не передан идентификатор пользователя');

            $requestedUser = User::getFromId($validator->validated()['userId']);
            if(!$requestedUser) return new Error('Пользователь не существует');

            if (!$userHelper->assignRoleAbility(
                $this->user->getRole(),
                $requestedUser->getRole())
            ) {
                throw new Exception('Невозможно удалить пользователя');
            }

            return new Success(
                $deleteUserAction->handle($requestedUser->getId())
            );

        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }


    /**
     * Восстановить пользователя
     * @param ResurrectionAction $resurrectionAction
     * @param UserHelper $userHelper
     * @return Success|Error
     */
    public function resurrectionUser(
        ResurrectionAction $resurrectionAction,
        UserHelper $userHelper
    ): Success|Error
    {
        try {
            $validator = Validator::make($this->request->all(), [
                'userId' => ['required', 'bail', 'int'],
            ]);

            if($validator->fails()) throw new Exception('Не передан идентификатор пользователя');

            $requestedUser = User::getFromId($validator->validated()['userId']);
            if(!$requestedUser) return new Error('Пользователь не существует');

            if ($this->user->getRole() !== 'admin') {
                throw new Exception('Невозможно восстановить пользователя');
            }

            return new Success($resurrectionAction->handle($requestedUser->getId()));

        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }
}
