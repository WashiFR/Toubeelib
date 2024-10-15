<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\rdv\ServiceRdvInvalidDataException;

class ChangerEtatRdvAction extends AbstractAction
{

    private ServiceRdvInterface $rdv_service;

    public function __construct(ServiceRdvInterface $rdv_service)
    {
        $this->rdv_service = $rdv_service;
    }
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();
        $statut = $data['statut'] ?? null;
        try {
            $this->rdv_service->getRdvById($data['id'])->updateStatut($args['id'], $statut);
        } catch(ServiceRdvInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
        $rdv = $this->rdv_service->getRdvById($args['id']);
        $rs->getBody()->write(json_encode($rdv));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}