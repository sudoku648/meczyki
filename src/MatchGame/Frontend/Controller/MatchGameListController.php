<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Provider\MatchGameDataTableProvider;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class MatchGameListController extends AbstractController
{
    public function list(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('match_games_list');

        return $this->render('match_game/list.html.twig');
    }

    public function fetch(MatchGameDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
