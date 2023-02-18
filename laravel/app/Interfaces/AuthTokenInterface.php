<?php

namespace App\Interfaces;

interface AuthTokenInterface
{
    public function getKey(): string;
    public function getExpired(): int;
    public function getFootprint(): string;
}
