<?php

declare(strict_types=1);

use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonCreateController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonDeleteController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonEditController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonListController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonPersonalInfoEditController;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonSingleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Routing\Requirement\Requirement;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('people_list', [
            'pl' => '/osoby',
        ])
        ->controller([PersonListController::class, 'list'])
        ->methods([Request::METHOD_GET]);

    $routes
        ->add('people_fetch', '/people/fetch')
        ->controller([PersonListController::class, 'fetch'])
        ->methods([Request::METHOD_POST]);

    $routes
        ->add('person_single', [
            'pl' => '/osoba/{person_id}',
        ])
        ->controller(PersonSingleController::class)
        ->requirements(['person_id' => Requirement::UUID_V6 . '|' . Requirement::UUID_V7])
        ->methods([Request::METHOD_GET]);

    $routes
        ->add('person_create', [
            'pl' => '/osoba/nowa',
        ])
        ->controller(PersonCreateController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes
        ->add('person_edit', [
            'pl' => '/osoba/{person_id}/edytuj',
        ])
        ->controller(PersonEditController::class)
        ->requirements(['person_id' => Requirement::UUID_V6 . '|' . Requirement::UUID_V7])
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes
        ->add('person_delete', [
            'pl' => '/osoba/{person_id}/usun',
        ])
        ->controller([PersonDeleteController::class, 'delete'])
        ->requirements(['person_id' => Requirement::UUID_V6 . '|' . Requirement::UUID_V7])
        ->methods([Request::METHOD_POST]);

    $routes
        ->add('person_delete_batch', [
            'pl' => '/osoby/usun',
        ])
        ->controller([PersonDeleteController::class, 'deleteBatch'])
        ->methods([Request::METHOD_POST]);

    $routes
        ->add('person_personal_info_edit', [
            'pl' => '/dane-osobowe',
        ])
        ->controller(PersonPersonalInfoEditController::class)
        ->methods([Request::METHOD_GET, Request::METHOD_POST]);

    $routes
        ->add('people_list_delegates', [
            'pl' => '/osoby/delegaci',
        ])
        ->controller([PersonListController::class, 'listDelegates'])
        ->methods([Request::METHOD_GET]);

    $routes
        ->add('people_list_referees', [
            'pl' => '/osoby/sedziowie',
        ])
        ->controller([PersonListController::class, 'listReferees'])
        ->methods([Request::METHOD_GET]);

    $routes
        ->add('people_list_referee_observers', [
            'pl' => '/osoby/obserwatorzy',
        ])
        ->controller([PersonListController::class, 'listRefereeObservers'])
        ->methods([Request::METHOD_GET]);

    $routes
        ->add('people_function_fetch', '/people/{function}s/fetch')
        ->controller([PersonListController::class, 'fetchWithFunction'])
        ->requirements(['function' => new EnumRequirement(MatchGameFunction::class)])
        ->methods([Request::METHOD_POST]);
};
