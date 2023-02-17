<?php

namespace App\Repository\EventsRepository;

use App\Models\Events;
use App\Repository\EventsRepository\DTO\UserEventsList;

class EventsRepository
{

    public function getUserEvents(int $userId)
    {
        return Events::query()
            ->leftJoin('users_event', function ($join) {
                $join->on('users_event.event_id', '=', 'events.id');
            })
            ->where('users_event.user_id', '=', $userId)
            ->orWhere('creator_id', '=', $userId)
            ->get()
            ->map(function ($item) use ($userId) {
                return new UserEventsList($item, $userId);
            });
    }
}
