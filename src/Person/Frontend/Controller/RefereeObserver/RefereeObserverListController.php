<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller\RefereeObserver;

use Sudoku648\Meczyki\Person\Frontend\DataTable\Provider\PersonDataTableProvider;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RefereeObserverListController extends RefereeObserverAbstractController
{
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        return $this->render('person/referee_observer/list.html.twig');
    }

    public function fetch(PersonDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide(onlyRefereeObserver: true));
    }
}
