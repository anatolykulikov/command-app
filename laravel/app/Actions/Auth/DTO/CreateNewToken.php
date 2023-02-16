<?php

namespace App\Actions\Auth\DTO;

use App\Helpers\UserHelper;
use App\Interfaces\AuthTokenInterface;

class CreateNewToken implements AuthTokenInterface
{
    protected string $key;
    protected int $expired;

    public function __construct()
    {
        $this->key = UserHelper::generateRandomPassword();
        $this->expired = time() + 60*60*24*30;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getExpired(): int
    {
        return $this->expired;
    }
}
