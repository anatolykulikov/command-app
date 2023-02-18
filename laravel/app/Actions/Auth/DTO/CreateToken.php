<?php

namespace App\Actions\Auth\DTO;

use App\Interfaces\AuthTokenInterface;

class CreateToken implements AuthTokenInterface
{
    protected string $key;
    protected int $expired;
    protected ?string $footprint;

    public function __construct(string $key, int $expired, ?string $footprint = null)
    {
        $this->key = $key;
        $this->expired = $expired;
        $this->footprint = $footprint;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getExpired(): int
    {
        return $this->expired;
    }

    public function getFootprint(): string
    {
        return $this->footprint;
    }
}
