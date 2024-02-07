<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Frontend\DataTable\DataTableGameTypeRow;
use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\Doctrine\DoctrineGameTypeRepository;
use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView\GameTypePageView;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\DataTableTrait;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeListController extends GameTypeAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::LIST);

        return $this->render('game_type/list.html.twig');
    }

    public function fetch(
        GameTypeRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(GameTypeVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new GameTypePageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrineGameTypeRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrineGameTypeRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->nameLike = $params['searches']['name'];

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var GameType $gameType */
        foreach ($objects as $objKey => $gameType) {
            $rows[] = new DataTableGameTypeRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'game_type/_datatable_checkbox.html.twig',
                    [
                        'gameTypeId' => $gameType->getId(),
                    ]
                ),
                $gameType->getName()->getValue(),
                $this->getButtonsForDataTable($gameType)
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

    private function getButtonsForDataTable(GameType $gameType): string
    {
        $buttons = '';

        if ($this->isGranted(GameTypeVoter::SHOW, $gameType)) {
            $buttons .= $this->renderView(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'game_type_single',
                    'parameters' => [
                        'game_type_id' => $gameType->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(GameTypeVoter::EDIT, $gameType)) {
            $buttons .= $this->renderView(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'game_type_edit',
                    'parameters' => [
                        'game_type_id' => $gameType->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(GameTypeVoter::DELETE, $gameType)) {
            $buttons .= $this->renderView(
                'game_type/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'gameType' => $gameType,
                ]
            );
        }

        return $buttons;
    }
}
