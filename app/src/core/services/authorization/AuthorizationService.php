<?php

namespace toubeelib\core\services\authorization;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\services\authorization\AuthzPraticienServiceInterface;

class AuthorizationService implements AuthzPraticienServiceInterface
{
    const PATIENT = 0;
    const PERSONNEL = 5;
    const PRATICIEN = 10;

    const READ = 0;
    const WRITE = 1;
    const DELETE = 2;

    public function isGranted(string $user_id, int $role, int $operation, string $ressource_id): bool
    {
        switch ($operation) {
            case $this::READ:
                return $role == $this::PATIENT || $role == $this::PERSONNEL || $role == $this::PRATICIEN;
            case $this::WRITE:
                return $role == $this::PERSONNEL || $role == $this::PRATICIEN;
            case $this::DELETE:
                return $role == $this::PERSONNEL;
            default:
                return false;
        }
    }
}