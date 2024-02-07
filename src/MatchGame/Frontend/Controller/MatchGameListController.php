<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\DataTable\DataTableMatchGameRow;
use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Doctrine\DoctrineMatchGameRepository;
use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\PageView\MatchGamePageView;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\DataTableTrait;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameListController extends MatchGameAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::LIST);

        return $this->render('match_game/list.html.twig');
    }

    public function fetch(
        MatchGameRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(MatchGameVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new MatchGamePageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrineMatchGameRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrineMatchGameRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->dateTimeLike = $params['searches']['dateTime'];
        $criteria->gameTypeLike = $params['searches']['gameType'];
        $criteria->teamsLike    = $params['searches']['teams'];

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var MatchGame $matchGame */
        foreach ($objects as $objKey => $matchGame) {
            $rows[] = new DataTableMatchGameRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'match_game/_datatable_checkbox.html.twig',
                    [
                        'matchGameId' => $matchGame->getId(),
                    ]
                ),
                $matchGame->getDateTime()->format('d.m.Y H:i'),
                $matchGame->getGameType()?->getName()->getValue() ?? '',
                ($matchGame->getHomeTeam()?->getName() ?? '<em class="text-black-50">nieznany</em>') .
                ' - ' .
                ($matchGame->getAwayTeam()?->getName() ?? '<em class="text-black-50">nieznany</em>'),
                $this->getButtonsForDataTable($matchGame)
            );
        }

        $dataTable = new DataTable(
            $params['draw'],
            $repository->getTotalCount(),
            $repository->countByCriteria($criteria),
            $rows,
        );

        return new JsonResponse($dataTable);
    }

    private function getButtonsForDataTable(MatchGame $matchGame): string
    {
        $buttons = '';

        if ($this->isGranted(MatchGameVoter::SHOW, $matchGame)) {
            $buttons .= $this->renderView(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'match_game_single',
                    'parameters' => [
                        'match_game_id' => $matchGame->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(MatchGameVoter::EDIT, $matchGame)) {
            $buttons .= $this->renderView(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'match_game_edit',
                    'parameters' => [
                        'match_game_id' => $matchGame->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(MatchGameVoter::DELETE, $matchGame)) {
            $buttons .= $this->renderView(
                'match_game/_delete_form.html.twig',
                [
                    'btn_size'  => 'table',
                    'matchGame' => $matchGame,
                ]
            );
        }

        return $buttons;
    }
}
