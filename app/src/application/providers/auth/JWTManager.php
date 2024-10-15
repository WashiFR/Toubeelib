<?php

namespace toubeelib\application\providers\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTManager
{
    private $secret;

    public function __construct()
    {
        $this->secret = getenv('JWT_SECRET_KEY');
    }

    public function createAccessToken(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600;
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function createRefreshToken(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600 * 24 * 7;
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decodeToken(string $token): array
    {
        try {
            $decodeToken = JWT::decode($token, new Key($this->secret, 'HS256'));
            return (array) $decodeToken;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}