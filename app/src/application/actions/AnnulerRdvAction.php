<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\rdv\ServiceRdvInvalidDataException;

class AnnulerRdvAction extends AbstractAction
{

    private ServiceRdvInterface $rdv_service;

    public function __construct(ServiceRdvInterface $rdv_service)
    {
        $this->rdv_service = $rdv_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        // Annulation du rendez-vous
        try {
            $this->rdv_service->annulerRendezVous($args['id']);
        } catch(ServiceRdvInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        // Récupération du rendez-vous
        try {
            $rdv = $this->rdv_service->getRdvById($args['id']);
        } catch(ServiceRdvInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $rs->getBody()->write(json_encode($rdv));

        // Redirection vers la ressource modifiée
        $routeContext = RouteContext::fromRequest($rq);
        $url = $routeContext->getRouteParser()->urlFor('rdvs', ['id' => $rdv->id]);

        return $rs->withHeader('Content-Type', $url)->withStatus(201);
    }
}