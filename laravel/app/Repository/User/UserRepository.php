<?php

namespace App\Repository\User;

use App\Actions\User\DTO\NewUserDTO;
use App\Models\User;
use App\Repository\User\DTO\CreateUserLoginResponse;

class UserRepository
{
    public function getUserByLogin(string $login): ?CreateUserLoginResponse
    {
        $search = User::query()
            ->where('login', '=', $login)
            ->get()
            ->first();
        if(!$search) return null;

        return new CreateUserLoginResponse(
            $search->id,
            $search->login,
            $search->password,
            boolval($search->active),
            boolval($search->deleted_at)
        );
    }

    public function createUser(NewUserDTO $user): int
    {
        return User::query()->insertGetId([
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'active' => $user->getActive()
        ]);
    }

    public function setAction(int $userId, bool $nextActive): bool
    {
        return User::query()
            ->where('id', '=', $userId)
            ->update(['active' => $nextActive,]);
    }

    public function deleteUser(int $userId): bool
    {
        return User::query()
            ->where('id', '=', $userId)
            ->update([
                'active' => false,
                'deleted_at' => date('Y-m-d H:m:s')
            ]);
    }

    public function recover(int $userId): bool
    {
        return User::query()
            ->where('id', '=', $userId)
            ->update([
                'active' => true,
                'deleted_at' => null
            ]);
    }

    public function getPassword(int $userId): string
    {
        return User::query()
            ->select('password')
            ->where('id', '=', $userId)
            ->get()
            ->pluck('password')
            ->first();
    }

    public function updatePassword(int $userId, string $password): bool
    {
        return User::query()
            ->where('id', '=', $userId)
            ->update(['password' => $password]);
    }
}
