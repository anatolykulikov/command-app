<?php

namespace App\Repository\User;

use App\Models\UserMeta;
use Illuminate\Database\Eloquent\Collection;

class UserMetaRepository
{

    public function getUserMeta(int $userId): Collection
    {
        return UserMeta::query()
            ->where('user_id', '=', $userId)
            ->get();
    }

    public function createUserMeta(int $userId, array $metas): bool
    {
        $queryParams = [];
        foreach ($metas as $key => $value) {
            $queryParams[] = [
                'user_id' => $userId,
                'key' => $key,
                'value' => $value
            ];
        }
        return UserMeta::query()->insert($queryParams);
    }

    public function updateUserMetas(int $userId, array $metas): bool
    {
        $queryParams = [];
        foreach ($metas as $key => $value) {
            $queryParams[] = [
                'user_id' => $userId,
                'key' => $key,
                'value' => $value
            ];
        }

        return UserMeta::query()->upsert(
            $queryParams,
            ['user_id', 'key'],
            ['value']
        );
    }

    public function deleteUserMeta(int $userId, array $metas): bool
    {
        return UserMeta::query()
            ->where('user_id', '=', $userId)
            ->whereIn('key', array_keys($metas))
            ->delete();
    }
}
