<?php

declare(strict_types=1);

use Sudoku648\Meczyki\Club\Frontend\Controller\ClubCreateController;
use Sudoku648\Meczyki\Club\Frontend\Controller\ClubDeleteController;
use Sudoku648\Meczyki\Club\Frontend\Controller\ClubDeleteEmblemController;
use Sudoku648\Meczyki\Club\Frontend\Controller\ClubEditController;
use Sudoku648\Meczyki\Club\Frontend\Controller\ClubListController;
use Sudoku648\Meczyki\Club\Frontend\Controller\ClubSingleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $clubId = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    $routes->add('clubs_list', '/kluby')
        ->controller([ClubListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes->add('clubs_fetch', '/clubs/fetch')
        ->controller([ClubListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('club_single', '/klub/{club_id}')
        ->controller(ClubSingleController::class)
        ->requirements(['club_id' => $clubId])
        ->methods([Request::METHOD_GET]);

    $routes->add('club_create', '/klub/nowy')
        ->controller(ClubCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('club_edit', '/klub/{club_id}/edytuj')
        ->controller(ClubEditController::class)
        ->requirements(['club_id' => $clubId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('club_delete', '/klub/{club_id}/usun')
        ->controller([ClubDeleteController::class, 'delete'])
        ->requirements(['club_id' => $clubId])
        ->methods([Request::METHOD_POST]);

    $routes->add('club_delete_batch', '/kluby/usun')
        ->controller([ClubDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('club_emblem_delete', '/klub/{club_id}/usun-herb')
        ->controller(ClubDeleteEmblemController::class)
        ->requirements(['club_id' => $clubId])
        ->methods([Request::METHOD_POST]);
};
