<?php

namespace App\Repository\AuthKeys;

use App\Actions\Auth\DTO\CreateNewToken;
use App\Models\AuthKeys;

class AuthKeysRepository
{

    public function create(int $userId, CreateNewToken $token): bool
    {
        return AuthKeys::query()->insert([
            'user' => $userId,
            'token' => $token->getKey(),
            'expired' => date('Y-m-d H:m:s', $token->getExpired())
        ]);
    }
}
