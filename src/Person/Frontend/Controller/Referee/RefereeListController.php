<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller\Referee;

use Sudoku648\Meczyki\Person\Frontend\DataTable\Provider\PersonDataTableProvider;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RefereeListController extends RefereeAbstractController
{
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        return $this->render('person/referee/list.html.twig');
    }

    public function fetch(PersonDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide(onlyReferee: true));
    }
}
