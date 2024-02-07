<?php

declare(strict_types=1);

use Sudoku648\Meczyki\Person\Frontend\Controller\Delegate\DelegateCreateController;
use Sudoku648\Meczyki\Person\Frontend\Controller\Delegate\DelegateListController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonCreateController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonDeleteController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonEditController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonListController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonPersonalInfoEditController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonSingleController;
use Sudoku648\Meczyki\Person\Frontend\Controller\Referee\RefereeCreateController;
use Sudoku648\Meczyki\Person\Frontend\Controller\Referee\RefereeListController;
use Sudoku648\Meczyki\Person\Frontend\Controller\RefereeObserver\RefereeObserverCreateController;
use Sudoku648\Meczyki\Person\Frontend\Controller\RefereeObserver\RefereeObserverListController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $personId = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[6-7][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    $routes->add('people_list', '/osoby')
        ->controller([PersonListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes->add('people_fetch', '/people/fetch')
        ->controller([PersonListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('person_single', '/osoba/{person_id}')
        ->controller(PersonSingleController::class)
        ->requirements(['person_id' => $personId])
        ->methods([Request::METHOD_GET]);

    $routes->add('person_create', '/osoba/nowa')
        ->controller(PersonCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('person_edit', '/osoba/{person_id}/edytuj')
        ->controller(PersonEditController::class)
        ->requirements(['person_id' => $personId])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes->add('person_delete', '/osoba/{person_id}/usun')
        ->controller([PersonDeleteController::class, 'delete'])
        ->requirements(['person_id' => $personId])
        ->methods([Request::METHOD_POST]);

    $routes->add('person_delete_batch', '/osoby/usun')
        ->controller([PersonDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);

    $routes->add('person_personal_info_edit', '/dane-osobowe')
        ->controller(PersonPersonalInfoEditController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    // @TODO to single person controller
    $routes->add('delegates_list', '/osoby/delegaci')
        ->controller([DelegateListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    // @TODO to single person controller
    $routes->add('delegate_fetch', '/people/delegates/fetch')
        ->controller([DelegateListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    // @TODO to single person controller
    $routes->add('delegate_create', '/osoby/delegat/nowy')
        ->controller(DelegateCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    // @TODO to single person controller
    $routes->add('referee_observers_list', '/osoby/obserwatorzy')
        ->controller([RefereeObserverListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    // @TODO to single person controller
    $routes->add('referee_observer_fetch', '/people/referee_observers/fetch')
        ->controller([RefereeObserverListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    // @TODO to single person controller
    $routes->add('referee_observer_create', '/osoby/obserwator/nowy')
        ->controller(RefereeObserverCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    // @TODO to single person controller
    $routes->add('referees_list', '/osoby/sedziowie')
        ->controller([RefereeListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    // @TODO to single person controller
    $routes->add('referee_fetch', '/people/referees/fetch')
        ->controller([RefereeListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    // @TODO to single person controller
    $routes->add('referee_create', '/osoby/sedzia/nowy')
        ->controller(RefereeCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);
};
