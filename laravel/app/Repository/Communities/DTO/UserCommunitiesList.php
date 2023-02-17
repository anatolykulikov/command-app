<?php

namespace App\Repository\Communities\DTO;

use App\Models\Communities;

class UserCommunitiesList
{
    public int $id;
    public string $type;
    public string $title;
    public bool $owner;
    public bool $public;

    public function __construct(Communities $community, int $userId)
    {
        $this->id = $community->getAttribute('id');
        $this->type = $community->getAttribute('type');
        $this->title = $community->getAttribute('title');
        $this->owner = $community->getAttribute('creator_id') === $userId;
        $this->public = boolval($community->getAttribute('public'));
    }
}
