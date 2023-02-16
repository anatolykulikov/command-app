<?php

namespace App\Actions\Auth\DTO;

use App\Interfaces\AuthTokenInterface;

class CreateToken implements AuthTokenInterface
{
    protected string $key;
    protected int $expired;

    public function __construct(string $key, int $expired)
    {
        $this->key = $key;
        $this->expired = $expired;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getExpired(): int
    {
        return $this->expired;
    }
}
