<?php

declare(strict_types=1);

use Sudoku648\Meczyki\Team\Frontend\Controller\TeamCreateController;
use Sudoku648\Meczyki\Team\Frontend\Controller\TeamDeleteController;
use Sudoku648\Meczyki\Team\Frontend\Controller\TeamEditController;
use Sudoku648\Meczyki\Team\Frontend\Controller\TeamListController;
use Sudoku648\Meczyki\Team\Frontend\Controller\TeamSingleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $clubId = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';
    $teamId = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    $routes->add('teams_list', '/kluby/druzyny')
        ->controller([TeamListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes->add('teams_fetch', '/clubs/teams/fetch')
        ->controller([TeamListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('team_single', '/klub/{club_id}/druzyna/{team_id}')
        ->controller(TeamSingleController::class)
        ->requirements([
            'club_id' => $clubId,
            'team_id' => $teamId,
        ])
        ->methods([Request::METHOD_GET]);

    $routes->add('team_create', '/klub/{club_id}/druzyna/nowa')
        ->controller(TeamCreateController::class)
        ->requirements(['club_id' => $clubId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('team_edit', '/klub/{club_id}/druzyna/{team_id}/edytuj')
        ->controller(TeamEditController::class)
        ->requirements([
            'club_id' => $clubId,
            'team_id' => $teamId,
        ])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('team_delete', '/klub/{club_id}/druzyna/{team_id}/usun')
        ->controller([TeamDeleteController::class, 'delete'])
        ->requirements([
            'club_id' => $clubId,
            'team_id' => $teamId,
        ])
        ->methods([Request::METHOD_POST]);

    $routes->add('team_delete_batch', '/kluby/druzyny/usun')
        ->controller([TeamDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);
};
