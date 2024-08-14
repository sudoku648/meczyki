<?php

declare(strict_types=1);

use Sudoku648\Meczyki\MatchGame\Frontend\Controller\MatchGameCreateController;
use Sudoku648\Meczyki\MatchGame\Frontend\Controller\MatchGameDeleteController;
use Sudoku648\Meczyki\MatchGame\Frontend\Controller\MatchGameEditController;
use Sudoku648\Meczyki\MatchGame\Frontend\Controller\MatchGameListController;
use Sudoku648\Meczyki\MatchGame\Frontend\Controller\MatchGameSingleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $matchGameId     = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    $routes->add('match_games_list', '/mecze')
        ->controller([MatchGameListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes->add('match_games_fetch', '/match_games/fetch')
        ->controller([MatchGameListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('match_game_single', '/mecz/{match_game_id}')
        ->controller(MatchGameSingleController::class)
        ->requirements(['match_game_id' => $matchGameId])
        ->methods([Request::METHOD_GET]);

    $routes->add('match_game_create', '/mecz/nowy')
        ->controller(MatchGameCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('match_game_edit', '/mecz/{match_game_id}/edytuj')
        ->controller(MatchGameEditController::class)
        ->requirements(['match_game_id' => $matchGameId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('match_game_delete', '/mecz/{match_game_id}/usun')
        ->controller([MatchGameDeleteController::class, 'delete'])
        ->requirements(['match_game_id' => $matchGameId])
        ->methods([Request::METHOD_POST]);

    $routes->add('match_game_delete_batch', '/mecze/usun')
        ->controller([MatchGameDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);
};
