<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\rdv\ServiceRdvInterface;

class AfficherPlanningAction extends AbstractAction
{

    private ServiceRdvInterface $rdv_service;

    public function __construct(ServiceRdvInterface $rdv_service)
    {
        $this->rdv_service = $rdv_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getQueryParams();
        $id = $args['id'];
        $date_debut = $data['date_debut'] ?? null;
        $date_fin = $data['date_fin'] ?? null;
        $rdv = $this->rdv_service->getRdvsByPraticienId($id, $date_debut, $date_fin);
        $data = ['type' => 'ressource', 'rdv' => $rdv];
        $rs->getBody()->write(json_encode($data));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}