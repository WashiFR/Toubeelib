<?php

namespace toubeelib\core\services\auth;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\User;
use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\services\auth\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(CredentialsDTO $credentials, int $role): string
    {
        $user = new User(Uuid::uuid4()->toString(), $credentials->getEmail(), $role);
        $user->setPassword(password_hash($credentials->getPassword(), PASSWORD_DEFAULT));
        return $this->userRepository->save($user);
    }

    public function byCredentials(CredentialsDTO $credentials): AuthDTO
    {
        $user = $this->userRepository->getUserByEmail($credentials->getEmail());

        if ($user && password_verify($credentials->getPassword(), $user->getPassword())) {
            return new AuthDTO($user);
        } else {
            throw new AuthServiceBadDataException('Erreur 400 : Email ou mot de passe incorrect', 400);
        }
    }
}