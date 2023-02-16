<?php

namespace App\Http\Controllers;

use App\Http\Responses\Success;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthorizedController extends BaseController
{
    protected ?User $user;

    public function __construct(
        Request $request
    )
    {
        $this->user = $request->user('web');
    }

    public function date(): Success
    {
        return new Success([
            'date' => date('c'),
            'user' => $this->user->getLogin()
        ]);
    }
}
