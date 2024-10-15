<?php

namespace toubeelib\application\providers\auth;

use PhpParser\Token;
use toubeelib\application\providers\auth\AuthProviderInterface;
use toubeelib\core\domain\entities\User;
use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\auth\AuthServiceInterface;

class JWTAuthProvider implements AuthProviderInterface
{
    private JWTManager $jwtManager;
    private AuthServiceInterface $authService;

    public function __construct(JWTManager $jwtManager, AuthServiceInterface $authService)
    {
        $this->jwtManager = $jwtManager;
        $this->authService = $authService;
    }

    public function register(CredentialsDTO $credentials, int $role): void
    {
        $this->authService->createUser($credentials, $role);
    }

    public function signin(CredentialsDTO $credentials): AuthDTO
    {
        $authDTO = $this->authService->byCredentials($credentials);
        $authDTO->setToken($this->jwtManager->createAccessToken([
            'id' => $authDTO->getId(),
            'email' => $authDTO->getEmail(),
            'role' => $authDTO->getRole()
        ]));
        $authDTO->setRefreshToken($this->jwtManager->createRefreshToken([
            'id' => $authDTO->getId(),
            'email' => $authDTO->getEmail(),
            'role' => $authDTO->getRole()
        ]));
        return $authDTO;
    }

    public function refresh(Token $token): AuthDTO
    {
        // TODO: Implement refresh() method.
    }

    public function getSignedInUser(Token $token): AuthDTO
    {
        $decodedToken = $this->jwtManager->decodeToken($token);
        $id = $decodedToken['id'];
        $email = $decodedToken['email'];
        $role = $decodedToken['role'];
        return new AuthDTO(new User($id, $email, $role));
    }
}