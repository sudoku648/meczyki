<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Controller\Traits\DataTableTrait;
use App\DataTable\DataTable;
use App\DataTable\DataTableGameTypeRow;
use App\Entity\GameType;
use App\PageView\GameTypePageView;
use App\Repository\GameTypeRepository;
use App\Security\Voter\GameTypeVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeFrontController extends GameTypeAbstractController
{
    use DataTableTrait;

    public function front(): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::LIST);

        return $this->render('game_type/index.html.twig');
    }

    public function fetch(
        GameTypeRepository $repository,
        Request $request
    ): JsonResponse {
        $this->denyAccessUnlessGranted(GameTypeVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new GameTypePageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? GameTypeRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? GameTypeRepository::SORT_DIR_DEFAULT;
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
                $gameType->getFullName(),
                $this->getButtonsForDataTable($gameType)
            );
        }

        $dataTable = new DataTable(
            $params['draw'],
            $repository->getTotalCount(),
            $repository->countByCriteria($criteria),
            $rows
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
