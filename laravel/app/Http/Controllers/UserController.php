<?php

namespace App\Http\Controllers;

use App\Actions\Auth\CurrentLogoutAction;
use App\Actions\User\UserProfileAction;
use App\Http\Responses\Success;
use App\Http\Responses\Error;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AuthorizedController
{
    protected CurrentLogoutAction $logoutAction;

    public function __construct(
        Request $request,
        CurrentLogoutAction $logoutAction
    )
    {
        $this->logoutAction = $logoutAction;
        parent::__construct($request);
    }

    public function getCurrent(): Success
    {
        return new Success(UserProfileAction::create($this->user));
    }

    public function getUser(int $userId): Success|Error
    {
        $requestedUser = User::getFromId($userId);
        if(!$requestedUser) return new Error('Пользователь не существует');
        return new Success(UserProfileAction::create($requestedUser));
    }

    public function logout(): Success|Error
    {
        $logout = $this->logoutAction->handle($this->request, $this->user);
        if($logout) return new Success('Вы вышли из системы');
        return new Error('Произошла ошибка при выходе из системы');
    }

    public function terminateOtherSessions()
    {
        dd($this->request);
    }

    public function terminateLogout()
    {
        dd($this->request);
    }
}
