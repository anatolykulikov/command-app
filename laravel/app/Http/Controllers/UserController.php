<?php

namespace App\Http\Controllers;

use App\Actions\User\UserProfileAction;
use App\Http\Responses\Success;

class UserController extends AuthorizedController
{
    public function getCurrent(): Success
    {
        return new Success(UserProfileAction::create($this->user));
    }

    public function getUser(int $userId): Success
    {
        return new Success([
            'пользователь' => $userId
        ]);
    }
}
