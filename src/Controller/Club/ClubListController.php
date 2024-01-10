<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Controller\Traits\DataTableTrait;
use App\DataTable\DataTable;
use App\DataTable\DataTableClubRow;
use App\Entity\Club;
use App\PageView\ClubPageView;
use App\Repository\Contracts\ClubRepositoryInterface;
use App\Repository\DoctrineClubRepository;
use App\Security\Voter\ClubVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubListController extends ClubAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::LIST);

        return $this->render('club/list.html.twig');
    }

    public function fetch(
        ClubRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(ClubVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new ClubPageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrineClubRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrineClubRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->nameLike = $params['searches']['name'];

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var Club $club */
        foreach ($objects as $objKey => $club) {
            $rows[] = new DataTableClubRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'club/_datatable_checkbox.html.twig',
                    [
                        'clubId' => $club->getId(),
                    ]
                ),
                $club->getName(),
                $this->getButtonsForDataTable($club)
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

    private function getButtonsForDataTable(Club $club): string
    {
        $buttons = '';

        if ($this->isGranted(ClubVoter::SHOW, $club)) {
            $buttons .= $this->renderView(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'club_single',
                    'parameters' => [
                        'club_id' => $club->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(ClubVoter::EDIT, $club)) {
            $buttons .= $this->renderView(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'club_edit',
                    'parameters' => [
                        'club_id' => $club->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(ClubVoter::DELETE, $club)) {
            $buttons .= $this->renderView(
                'club/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'club'     => $club,
                ]
            );
        }

        return $buttons;
    }
}
