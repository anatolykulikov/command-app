<?php

namespace App\Repository\Teams;

use App\Models\Teams;
use App\Repository\Teams\DTO\UserTeamsList;
use Illuminate\Support\Collection;

class TeamsRepository
{

    public function getUserTeams(int $userId): Collection
    {
        return Teams::query()
            ->leftJoin('users_binding_teams', function ($join) {
                $join->on('users_binding_teams.team_id', '=', 'teams.id');
            })
            ->where('users_binding_teams.user_id', '=', $userId)
            ->orWhere('creator_id', '=', $userId)
            ->get()
            ->map(function ($task) use ($userId) {
                return new UserTeamsList($task, $userId);
            });
    }
}
