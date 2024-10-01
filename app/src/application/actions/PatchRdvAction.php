<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\rdv\ServiceRdvInvalidDataException;

class PatchRdvAction extends AbstractAction
{
    private ServiceRdvInterface $rdv_service;

    public function __construct(ServiceRdvInterface $rdv_service)
    {
        $this->rdv_service = $rdv_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();

        $rdvInputValidator = Validator::key('specialite', Validator::stringType())
            ->key('id_patient', Validator::intVal());

        try {
            $rdvInputValidator->assert($data);
        } catch(\Respect\Validation\Exceptions\NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getFullMessage());
        }

        // Modification du rendez-vous et gestion des erreurs
        if (isset($data['specialite'])) {
            try {
                $this->rdv_service->modifierSpecialiteRendezVous($args['id'], $data['specialite']);
            } catch (ServiceRdvInvalidDataException $e) {
                throw new HttpBadRequestException($rq, $e->getMessage());
            }
        }
        if (isset($data['id_patient'])) {
            try {
                $this->rdv_service->modifierPatientRendezVous($args['id'], $data['id_patient']);
            } catch(ServiceRdvInvalidDataException $e) {
                throw new HttpBadRequestException($rq, $e->getMessage());
            }
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