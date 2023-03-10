<?php

namespace App\Http\Controllers;

use App\Actions\Auth\AuthAction;
use App\Http\Responses\Error;
use App\Http\Responses\Success;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @param AuthAction $authAction
     * @return Error|Success
     */
    public function login(
        Request $request,
        AuthAction $authAction
    ): Error|Success
    {
        try {
            return new Success($authAction->handle($request));
        } catch (Exception $exception) {
            return new Error($exception->getMessage());
        }
    }
}
