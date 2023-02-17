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
}
