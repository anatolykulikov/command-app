<?php

namespace App\Repository\Communities;

use App\Models\Communities;
use App\Repository\Communities\DTO\UserCommunitiesList;
use Illuminate\Support\Collection;

class CommunitiesRepository
{

    public function getUserCommunity(int $userId): Collection
    {
        return Communities::query()
            ->leftJoin('users_binding_communities', function ($join) {
                $join->on('users_binding_communities.community_id', '=', 'communities.id');
            })
            ->where('users_binding_communities.user_id', '=', $userId)
            ->orWhere('creator_id', '=', $userId)
            ->get()
            ->map(function ($item) use ($userId) {
                return new UserCommunitiesList($item, $userId);
            });
    }

}
