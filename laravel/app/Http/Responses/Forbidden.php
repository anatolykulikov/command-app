<?php

namespace App\Http\Responses;

use App\Helpers\ResponseCreator;
use Illuminate\Http\JsonResponse;

class Forbidden extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(
            ResponseCreator::createResponse('forbidden', null, 'Не хватает прав для выполнения задачи'),
            403
        );
    }
}
