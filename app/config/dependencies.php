<?php

use Psr\Container\ContainerInterface;
use toubeelib\application\actions\AnnulerRdvAction;
use toubeelib\application\actions\GetDispoPraticienAction;
use toubeelib\application\actions\GetPatientRdvsAction;
use toubeelib\application\actions\GetPraticienByIdAction;
use toubeelib\application\actions\GetPraticiensActions;
use toubeelib\application\actions\GetRdvByIdAction;
use toubeelib\application\actions\PatchRdvAction;
use toubeelib\application\actions\PostNewRdvAction;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\ServiceRdv;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\infrastructure\repositories\PDOPraticienRepository;
use toubeelib\infrastructure\repositories\PDORdvRepository;

return [

    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository();
    },

    RdvRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDORdvRepository();
    },

    ServiceRdvInterface::class => function (ContainerInterface $c) {
        return new ServiceRdv(
            $c->get(PraticienRepositoryInterface::class),
            $c->get(RdvRepositoryInterface::class)
        );
    },

    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien(
            $c->get(PraticienRepositoryInterface::class)
        );
    },

    // #####################
    //      Actions
    // #####################

    AnnulerRdvAction::class => function (ContainerInterface $c) {
        return new AnnulerRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    GetDispoPraticienAction::class => function (ContainerInterface $c) {
        return new GetDispoPraticienAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    GetPatientRdvsAction::class => function (ContainerInterface $c) {
        return new GetPatientRdvsAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    GetPraticienByIdAction::class => function (ContainerInterface $c) {
        return new GetPraticienByIdAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    GetPraticiensActions::class => function (ContainerInterface $c) {
        return new GetPraticiensActions(
            $c->get(ServicePraticienInterface::class)
        );
    },

    GetRdvByIdAction::class => function (ContainerInterface $c) {
        return new GetRdvByIdAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    PatchRdvAction::class => function (ContainerInterface $c) {
        return new PatchRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    PostNewRdvAction::class => function (ContainerInterface $c) {
        return new PostNewRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

];