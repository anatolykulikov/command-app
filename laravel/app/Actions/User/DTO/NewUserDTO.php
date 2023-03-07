<?php

namespace App\Actions\User\DTO;

use App\Helpers\UserHelper;
use App\Interfaces\UserInterface;

class NewUserDTO implements UserInterface
{
    public ?int $id;
    public string $login;
    public string $password;
    public bool $active;
    public string $role;
    public array $metas;

    public function __construct(array $validatedData)
    {
        $this->id = null;
        $this->login = $validatedData['login'];
        $this->password = (new UserHelper())->hashPassword($validatedData['password']);
        $this->active = $validatedData['active'];
        $this->role = $validatedData['role'];
        $this->metas = $validatedData['metas'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id = null)
    {
        $this->id = $id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getMetas(): array
    {
        return $this->metas;
    }
}
