<?php

use Psr\Container\ContainerInterface;
use toubeelib\application\actions\GetRdvByIdAction;
use toubeelib\application\actions\PostNewRdvAction;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\rdv\ServiceRdv;
use toubeelib\core\services\rdv\ServiceRdvInterface;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

return [

    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new ArrayPraticienRepository();
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
    }

];