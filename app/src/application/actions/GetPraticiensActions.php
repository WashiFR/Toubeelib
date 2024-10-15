<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\praticien\ServicePraticienInterface;

class GetPraticiensActions extends AbstractAction
{
    private ServicePraticienInterface $praticienService;

    public function __construct(ServicePraticienInterface $praticienService)
    {
        $this->praticienService = $praticienService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $praticiens = $this->praticienService->getAllPraticiens();
        $data = ['type' => 'collection', 'praticiens' => $praticiens];
        $rs->getBody()->write(json_encode($data));
        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}