<?php

namespace App\Repository\Tasks\DTO;

use App\Models\Tasks;

class CreateListTasksItem
{
    public int $id;
    public int $executorId;
    public ?int $statusId;
    public ?int $priorityId;
    public string $title;

    public function __construct(Tasks $task)
    {
        $this->id = $task->getAttributeValue('id');
        $this->executorId = $task->getAttributeValue('executor_id');
        $this->statusId = $task->getAttributeValue('status_id');
        $this->priorityId = $task->getAttributeValue('priority_id');
        $this->title = $task->getAttributeValue('title');
    }
}
