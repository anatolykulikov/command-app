<?php

namespace App\Http\Responses;

use App\Helpers\ResponseCreator;
use Illuminate\Http\JsonResponse;

class Success extends JsonResponse
{
    public function __construct(mixed $data = null, string $message = null, $status = 200)
    {
        parent::__construct(
            ResponseCreator::createResponse('ok', $data, $message),
            $status
        );
    }
}
