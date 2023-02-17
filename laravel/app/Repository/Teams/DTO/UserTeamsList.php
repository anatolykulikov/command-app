<?php

namespace App\Repository\Teams\DTO;

use App\Models\Teams;

class UserTeamsList
{
    public int $id;
    public string $title;
    public bool $owner;
    public bool $public;

    public function __construct(Teams $team, int $userId)
    {
        $this->id = $team->getAttribute('id');
        $this->title = $team->getAttribute('title');
        $this->owner = $team->getAttribute('creator_id') === $userId;
        $this->public = boolval($team->getAttribute('public'));
    }
}
