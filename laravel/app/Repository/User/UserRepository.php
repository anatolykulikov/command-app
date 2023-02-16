<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\User\DTO\CreateUserLoginResponse;

class UserRepository
{
    public function getUserByLogin(string $login): ?CreateUserLoginResponse
    {
        $search = User::query()->where('login', '=', $login)->get()->first();
        if(!$search) return null;
        return new CreateUserLoginResponse($search->id, $search->login, $search->password);
    }

}
