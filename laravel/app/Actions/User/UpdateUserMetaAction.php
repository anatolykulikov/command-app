<?php

namespace App\Actions\User;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\User\UserMetaRepository;

class UpdateUserMetaAction
{
    protected UserMetaRepository $userMetaRepository;
    protected array $forUpdate;
    protected array $forDelete;

    public function __construct(
        UserMetaRepository $userMetaRepository
    )
    {
        $this->userMetaRepository = $userMetaRepository;
        $this->forUpdate = [];
        $this->forDelete = [];
    }

    /**
     * @param Request $request
     * @param User $user
     * @return string
     */
    public function handle(Request $request, User $user): string
    {
        foreach ($request->toArray() as $key => $value) {
            if($value && $value !== '') $this->forUpdate[$key] = $value;
            if(!$value) $this->forDelete[$key] = $value;
        }

        $update = $this->userMetaRepository->updateUserMetas($user->getId(), $this->forUpdate);
        $delete = $this->userMetaRepository->deleteUserMeta($user->getId(), $this->forDelete);

        return $update || $delete
            ? 'Мета-данные обновлены'
            : 'Ничего не обновлено';
    }
}
