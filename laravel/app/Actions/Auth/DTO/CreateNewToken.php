<?php

namespace App\Actions\Auth\DTO;

use App\Helpers\UserHelper;
use App\Interfaces\AuthTokenInterface;

class CreateNewToken implements AuthTokenInterface
{
    protected string $key;
    protected int $expired;
    protected string $footprint;

    public function __construct()
    {
        $this->key = UserHelper::generateRandomPassword();
        $this->expired = time() + 60*60*24*30;
        $this->footprint = $this->generateFootprint();
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

    protected function generateFootprint(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * @return string
     */
    public function getFootprint(): string
    {
        return $this->footprint;
    }
}
