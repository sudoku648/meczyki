<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Provider\TeamDataTableProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TeamListController extends AbstractController
{
    public function list(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('teams_list');

        return $this->render('team/list.html.twig');
    }

    public function fetch(TeamDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
