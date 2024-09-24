<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\InputRdvDTO;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\rdv\ServiceRdvInvalidDataException;

class PostNewRdvAction extends AbstractAction
{
    private ServiceRdvInterface $rdv_service;

    public function __construct(ServiceRdvInterface $rdv_service)
    {
        $this->rdv_service = $rdv_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();

        // Validation des données
        $rdvInputValidator = Validator::key('id_praticien', Validator::intVal()->notEmpty())
            ->key('id_patient', Validator::intVal()->notEmpty())
            ->key('specialite', Validator::stringType()->notEmpty())
            ->key('date', Validator::stringType()->notEmpty());

        if (!$rdvInputValidator->validate($data)) {
            throw new HttpBadRequestException($rq, 'Données invalides');
        }

        // Création du input DTO
        $input_rdv = new InputRdvDTO($data['id_praticien'], $data['id_patient'], $data['specialite'], $data['date']);

        // Création du rendez-vous et gestion des erreurs
        try {
            $rdv = $this->rdv_service->creerRendezVous($input_rdv);
        } catch(ServiceRdvInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        $rs->getBody()->write(json_encode($rdv));

        // Redirection vers la ressource créée
        $routeContext = RouteContext::fromRequest($rq);
        $url = $routeContext->getRouteParser()->urlFor('rdvs', ['id' => $rdv->id]);

        return $rs->withHeader('Content-Type', $url)->withStatus(201);
    }
}