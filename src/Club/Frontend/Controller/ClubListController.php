<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Frontend\DataTable\Provider\ClubDataTableProvider;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ClubListController extends AbstractController
{
    public function list(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('clubs_list');

        return $this->render('club/list.html.twig');
    }

    public function fetch(ClubDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
