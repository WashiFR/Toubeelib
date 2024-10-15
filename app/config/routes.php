<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    // Affiche un rendez-vous
    $app->get('/rdvs/{id}', \toubeelib\application\actions\GetRdvByIdAction::class)
        ->setName('rdvs');
    // Modifier un rendez-vous
    $app->patch('/rdvs/{id}', \toubeelib\application\actions\PatchRdvAction::class);
    // Annuler un rendez-vous
    $app->delete('/rdvs/{id}', \toubeelib\application\actions\AnnulerRdvAction::class);
    // Crée un rendez-vous
    $app->post('/rdvs', \toubeelib\application\actions\PostNewRdvAction::class);

    // Affiche les rendez-vous d'un patient
    $app->get('/patient/{id}/rdvs', \toubeelib\application\actions\GetPatientRdvsAction::class);

    // Affiches tous les praticiens
    $app->get('/praticiens', \toubeelib\application\actions\GetPraticiensActions::class)
        ->setName('praticiens');
    // Affiche un praticien
    $app->get('/praticiens/{id}', \toubeelib\application\actions\GetPraticienByIdAction::class);
    // Affiche les disponibilités d'un praticien
    $app->get('/praticiens/{id}/dispo', \toubeelib\application\actions\GetDispoPraticienAction::class)
        ->setName('dispo');

    // S'authentifier
    $app->post('/signin', \toubeelib\application\actions\PostSigninAction::class)
        ->setName('signin');

    return $app;
};