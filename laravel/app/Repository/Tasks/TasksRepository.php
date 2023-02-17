<?php

namespace App\Repository\Tasks;

use App\Models\Tasks;
use App\Repository\Tasks\DTO\CreateListTasksItem;
use Illuminate\Support\Collection;

class TasksRepository
{
    public function getListByUser(int $userId, bool $onlyActive = true): Collection|array
    {
        return Tasks::query()
            ->where('executor_id', '=', $userId)
            ->orWhere('creator_id', '=', $userId)
            ->where('is_open', '=', $onlyActive)
            ->whereNotNull('deleted_at')
            ->get()
            ->map(function ($task) {
                return new CreateListTasksItem($task);
            });
    }
}
