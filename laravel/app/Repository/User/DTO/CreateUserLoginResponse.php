<?php

namespace App\Repository\User\DTO;

class CreateUserLoginResponse
{
    protected int $id;
    protected string $login;
    protected string $password;
    protected bool $isActive;
    protected bool $isDeleted;

    public function __construct(
        int $id,
        string $login,
        string $password,
        bool $isActive,
        bool $isDeleted
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }
}
