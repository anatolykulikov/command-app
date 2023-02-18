<?php

namespace App\Repository\AuthKeys;

use App\Actions\Auth\DTO\CreateNewToken;
use App\Models\AuthKeys;

class AuthKeysRepository
{

    public function create(int $userId, CreateNewToken $token): bool
    {
        return AuthKeys::query()->insert([
            'user_id' => $userId,
            'token' => $token->getKey(),
            'footprint' => $token->getFootprint(),
            'expired' => date('Y-m-d H:m:s', $token->getExpired())
        ]);
    }

    public function deleteUserToken(int $userId, string $token)
    {
        return AuthKeys::query()
            ->where('user_id', '=', $userId)
            ->where('token', '=', $token)
            ->delete();
    }

    public function deleteOtherUserTokens(int $userId, string $token)
    {
        return AuthKeys::query()
            ->where('user_id', '=', $userId)
            ->whereNot('token', '=', $token)
            ->delete();
    }

    public function deleteAllUserTokens(int $userId)
    {
        return AuthKeys::query()
            ->where('user_id', '=', $userId)
            ->delete();
    }

    // TODO: Сделать удаление просроченных токенов на CRON Job
}
