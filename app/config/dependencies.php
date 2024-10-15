<?php

use Psr\Container\ContainerInterface;
use toubeelib\application\actions\AnnulerRdvAction;
use toubeelib\application\actions\GetDispoPraticienAction;
use toubeelib\application\actions\GetRdvByIdAction;
use toubeelib\application\actions\PatchRdvAction;
use toubeelib\application\actions\PostNewRdvAction;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\rdv\ServiceRdv;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\infrastructure\repositories\PDOPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

return [

    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository();
    },

    RdvRepositoryInterface::class => function (ContainerInterface $c) {
        return new ArrayRdvRepository();
    },

    ServiceRdvInterface::class => function (ContainerInterface $c) {
        return new ServiceRdv(
            $c->get(PraticienRepositoryInterface::class),
            $c->get(RdvRepositoryInterface::class)
        );
    },

    GetRdvByIdAction::class => function (ContainerInterface $c) {
        return new GetRdvByIdAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    PostNewRdvAction::class => function (ContainerInterface $c) {
        return new PostNewRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    PatchRdvAction::class => function (ContainerInterface $c) {
        return new PatchRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

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

];