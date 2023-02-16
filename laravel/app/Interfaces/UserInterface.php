<?php

namespace App\Interfaces;

interface UserInterface
{
    public function getId(): int;
    public function getLogin(): string;
    public function getRole(): string;
    public function getActive(): bool;
}
