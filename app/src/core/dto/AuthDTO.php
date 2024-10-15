<?php

namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\User;
use toubeelib\core\dto\DTO;

class AuthDTO extends DTO
{
    protected string $id;
    protected string $email;
    protected int $role;
    protected string $token;
    protected string $refreshToken;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->role = $user->getRole();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}