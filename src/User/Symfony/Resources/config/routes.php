<?php

declare(strict_types=1);

use Sudoku648\Meczyki\User\Frontend\Controller\Profile\UserProfileController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserActivationController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserBindWithPersonController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserCreateController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserDeleteController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserEditController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserListController;
use Sudoku648\Meczyki\User\Frontend\Controller\UserSingleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $userId = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    $routes->add('users_list', '/uzytkownicy')
        ->controller([UserListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes->add('users_fetch', '/users/fetch')
        ->controller([UserListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_single', '/uzytkownik/{user_id}')
        ->controller(UserSingleController::class)
        ->requirements(['user_id' => $userId])
        ->methods([Request::METHOD_GET]);

    $routes->add('user_create', '/uzytkownik/nowy')
        ->controller(UserCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('user_edit', '/uzytkownik/{user_id}/edytuj')
        ->controller(UserEditController::class)
        ->requirements(['user_id' => $userId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('user_delete', '/uzytkownik/{user_id}/usun')
        ->controller([UserDeleteController::class, 'delete'])
        ->requirements(['user_id' => $userId])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_delete_batch', '/uzytkownicy/usun')
        ->controller([UserDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_activate', '/uzytkownik/{user_id}/aktywuj')
        ->controller([UserActivationController::class, 'activate'])
        ->requirements(['user_id' => $userId])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_deactivate', '/uzytkownik/{user_id}/dezaktywuj')
        ->controller([UserActivationController::class, 'deactivate'])
        ->requirements(['user_id' => $userId])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_activate_batch', '/uzytkownicy/aktywuj')
        ->controller([UserActivationController::class, 'activateBatch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_deactivate_batch', '/uzytkownicy/dezaktywuj')
        ->controller([UserActivationController::class, 'deactivateBatch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('user_profile', '/profil')
        ->controller(UserProfileController::class)
        ->methods([Request::METHOD_GET]);

    $routes->add('user_bind_with_person', '/uzytkownik/{user_id}/polacz-z-osoba')
        ->controller(UserBindWithPersonController::class)
        ->requirements(['user_id' => $userId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);
};
