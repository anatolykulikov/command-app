<?php

namespace App\Http\Responses;

use App\Helpers\ResponseCreator;
use Illuminate\Http\JsonResponse;

class Unauthorized extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(
            ResponseCreator::createResponse('unauthorized', null, 'Пользователь не авторизован'),
            401
        );
    }
}
