<?php

namespace App\Http\Controllers;

use App\Actions\Auth\AuthAction;
use App\Http\Responses\Error;
use App\Http\Responses\Success;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthAction $authAction;

    public function __construct(AuthAction $authAction)
    {
        $this->authAction = $authAction;
    }

    public function login(Request $request): Error|Success
    {
        try {
            return new Success($this->authAction->handle($request));
        } catch (\Exception $exception) {
            return new Error($exception->getMessage());
        }
    }
}
