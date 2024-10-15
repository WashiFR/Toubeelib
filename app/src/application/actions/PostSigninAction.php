<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\providers\auth\AuthProviderInterface;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\auth\AuthServiceBadDataException;

class PostSigninAction extends AbstractAction
{

    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        try {
            $authDTO = $this->authProvider->signin(new CredentialsDTO($email, $password));
            $token = $authDTO->getToken();
            $refreshToken = $authDTO->getRefreshToken();
        } catch (AuthServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $rs->getBody()->write(json_encode([
            'token' => $token,
            'refreshToken' => $refreshToken
        ]));

        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}