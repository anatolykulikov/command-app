<?php

namespace App\Actions\User;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\User\UserMetaRepository;

class UpdateUserMetaAction
{
    protected UserMetaRepository $userMetaRepository;

    public function __construct(
        UserMetaRepository $userMetaRepository
    )
    {
        $this->userMetaRepository = $userMetaRepository;
    }

    public function handle(Request $request, User $user): array
    {
        return [
            'request' => $request->toArray(),
            'user' => $user
        ];
    }
}
