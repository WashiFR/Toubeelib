<?php

namespace toubeelib\core\services\auth;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\User;
use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\auth\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createUser(CredentialsDTO $credentials, int $role): string
    {
        $this->user->setId(Uuid::uuid4()->toString());
        $this->user->setEmail($credentials->getEmail());
        $this->user->setPassword(password_hash($credentials->getPassword(), PASSWORD_DEFAULT));
        $this->user->setRole($role);
        return $this->user->getId();
    }

    public function byCredentials(CredentialsDTO $credentials): AuthDTO
    {
        $user = $this->user->where('email', $credentials->getEmail())->first();

        if ($user && password_verify($credentials->getPassword(), $user->getPassword())) {
            return new AuthDTO($user);
        } else {
            throw new AuthServiceBadDataException('Erreur 400 : Email ou mot de passe incorrect', 400);
        }
    }
}