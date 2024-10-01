<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    // Affiche un rendez-vous
    $app->get('/rdvs/{id}', \toubeelib\application\actions\GetRdvByIdAction::class)->setName('rdvs');
    // Modifier un rendez-vous
    $app->patch('/rdvs/{id}', \toubeelib\application\actions\PatchRdvAction::class);
    // Crée un rendez-vous
    $app->post('/rdvs', \toubeelib\application\actions\PostNewRdvAction::class);

    return $app;
};