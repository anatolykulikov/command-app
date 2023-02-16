<?php

namespace App\Http\Responses;

use App\Helpers\ResponseCreator;
use Illuminate\Http\JsonResponse;

class Error extends JsonResponse
{
    public function __construct(mixed $data = null, string $message = null, $status = 400)
    {
        parent::__construct(
            ResponseCreator::createResponse('error', $data, $message),
            $status
        );
    }
}
