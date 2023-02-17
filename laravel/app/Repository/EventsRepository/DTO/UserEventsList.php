<?php

namespace App\Repository\EventsRepository\DTO;

use App\Models\Events;

class UserEventsList
{
    public int $id;
    public string $type;
    public string $title;
    public bool $owner;
    public bool $repeat;
    public string $startedAt;

    public function __construct(Events $event, int $userId)
    {
        $this->id = $event->getAttribute('id');
        $this->type = $event->getAttribute('type');
        $this->title = $event->getAttribute('title');
        $this->owner = $event->getAttribute('creator_id') === $userId;
        $this->repeat = boolval($event->getAttribute('repeat'));
        $this->startedAt = $event->getAttribute('started_at');
    }
}
