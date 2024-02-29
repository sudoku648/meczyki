<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Provider\TeamDataTableProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TeamListController extends TeamAbstractController
{
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::LIST);

        return $this->render('team/list.html.twig');
    }

    public function fetch(TeamDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
