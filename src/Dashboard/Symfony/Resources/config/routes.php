<?php

declare(strict_types=1);

use Sudoku648\Meczyki\Dashboard\Frontend\Controller\DashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('dashboard', '/')
        ->controller(DashboardController::class)
        ->methods([Request::METHOD_GET]);
};
