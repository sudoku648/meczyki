<?php

declare(strict_types=1);

use Sudoku648\Meczyki\GameType\Frontend\Controller\GameTypeCreateController;
use Sudoku648\Meczyki\GameType\Frontend\Controller\GameTypeDeleteController;
use Sudoku648\Meczyki\GameType\Frontend\Controller\GameTypeDeleteImageController;
use Sudoku648\Meczyki\GameType\Frontend\Controller\GameTypeEditController;
use Sudoku648\Meczyki\GameType\Frontend\Controller\GameTypeListController;
use Sudoku648\Meczyki\GameType\Frontend\Controller\GameTypeSingleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $gameTypeId = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    $routes->add('game_types_list', '/typy-rozgrywek')
        ->controller([GameTypeListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes->add('game_types_fetch', '/game_types/fetch')
        ->controller([GameTypeListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('game_type_single', '/typ-rozgrywek/{game_type_id}')
        ->controller(GameTypeSingleController::class)
        ->requirements(['game_type_id' => $gameTypeId])
        ->methods([Request::METHOD_GET]);

    $routes->add('game_type_create', '/typ-rozgrywek/nowy')
        ->controller(GameTypeCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('game_type_edit', '/typ-rozgrywek/{game_type_id}/edytuj')
        ->controller(GameTypeEditController::class)
        ->requirements(['game_type_id' => $gameTypeId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('game_type_delete', '/typ-rozgrywek/{game_type_id}/usun')
        ->controller([GameTypeDeleteController::class, 'delete'])
        ->requirements(['game_type_id' => $gameTypeId])
        ->methods([Request::METHOD_POST]);

    $routes->add('game_type_delete_batch', '/typy-rozgrywek/usun')
        ->controller([GameTypeDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('game_type_image_delete', '/typ-rozgrywek/{game_type_id}/usun-obrazek')
        ->controller(GameTypeDeleteImageController::class)
        ->requirements(['game_type_id' => $gameTypeId])
        ->methods([Request::METHOD_POST]);
};
