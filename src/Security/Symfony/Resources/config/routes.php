<?php

declare(strict_types=1);

use Sudoku648\Meczyki\Security\Frontend\Controller\LoginController;
use Sudoku648\Meczyki\Security\Frontend\Controller\LogoutController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('app_login', [
            'pl' => '/zaloguj',
        ])
        ->controller(LoginController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes
        ->add('app_logout', [
            'pl' => '/wyloguj',
        ])
        ->controller([LogoutController::class, 'logout'])
        ->methods([Request::METHOD_GET]);
};
