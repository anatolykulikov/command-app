<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthorizedController extends BaseController
{
    protected Request $request;
    protected ?User $user;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
        $this->user = $request->user('web');
    }
}
