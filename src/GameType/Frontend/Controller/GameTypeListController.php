<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Frontend\DataTable\Provider\GameTypeDataTableProvider;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GameTypeListController extends AbstractController
{
    public function list(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('game_types_list');

        return $this->render('game_type/list.html.twig');
    }

    public function fetch(GameTypeDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
