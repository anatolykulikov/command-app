<?php

namespace App\Http\Controllers;

use App\Actions\User\UserProfileAction;
use App\Http\Responses\Success;
use App\Http\Responses\Error;
use App\Models\User;

class UserController extends AuthorizedController
{
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
}
