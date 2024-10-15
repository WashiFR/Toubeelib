<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\core\services\rdv\ServiceRdvInvalidDataException;

class GetDispoPraticienAction extends AbstractAction
{
    private ServiceRdvInterface $rdv_service;

    public function __construct(ServiceRdvInterface $rdv_service)
    {
        $this->rdv_service = $rdv_service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $id = $args['id'];
        $data = $rq->getQueryParams();

        $rdvInputValidator = Validator::key('date_debut', Validator::dateTime())
            ->key('date_fin', Validator::dateTime());

        try {
            $rdvInputValidator->assert($data);
        } catch(\Respect\Validation\Exceptions\NestedValidationException $e) {
            throw new HttpBadRequestException($rq, $e->getFullMessage());
        }

        try {
            $rdv = $this->rdv_service->getDisponibilitesPraticien($id, $data['date_debut'], $data['date_fin']);
        } catch(ServiceRdvInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $data = ['type' => 'ressource', 'rdv' => $rdv];
        $rs->getBody()->write(json_encode($data));

        return $rs
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}