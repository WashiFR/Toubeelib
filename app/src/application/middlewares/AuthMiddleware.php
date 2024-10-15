<?php

namespace toubeelib\application\middlewares;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\application\providers\auth\AuthProviderInterface;

class AuthMiddleware
{
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(Request $rq, callable $next) : Response
    {
        try {
            $token = $rq->getHeader('Authorization')[0];
            $tokenstring = sscanf($token, 'Bearer %s')[0];
            $authDTO = $this->authProvider->getSignedInUser($tokenstring);
        } catch (ExpiredException|\UnexpectedValueException|BeforeValidException|SignatureInvalidException $e) {
            return $next($rq)->withStatus(401);
        }

        $rq = $rq->withAttribute('auth', $authDTO);

        return $next($rq);
    }
}