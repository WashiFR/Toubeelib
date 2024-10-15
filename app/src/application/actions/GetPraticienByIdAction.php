<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;

class GetPraticienByIdAction extends AbstractAction
{
    private ServicePraticienInterface $praticienService;

    public function __construct(ServicePraticienInterface $praticienService)
    {
        $this->praticienService = $praticienService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $id = $args['id'];

        try {
            $praticien = $this->praticienService->getPraticienById($id);
        } catch(ServicePraticienInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $data = ['type' => 'ressource', 'praticien' => $praticien];
        $rs->getBody()->write(json_encode($data));
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}