<?php

namespace toubeelib\core\domain\entities;

use toubeelib\core\dto\AuthDTO;

class User extends Entity
{
    protected string $id;
    protected string $email;
    protected string $password;
    protected int $role;

    public function __construct(string $id, string $email, int $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRole(int $role): void
    {
        $this->role = $role;
    }

    public function toDTO(): AuthDTO
    {
        return new AuthDTO($this);
    }
}