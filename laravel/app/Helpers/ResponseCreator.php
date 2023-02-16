<?php

namespace App\Helpers;

class ResponseCreator
{
    public static function createResponse(
        string $code,
        mixed $data,
        string $message = null
    ): array
    {
        $responseBody = ['code' => $code];
        if($data) $responseBody['data'] = $data;
        if($message) $responseBody['message'] = $message;
        return $responseBody;
    }
}
