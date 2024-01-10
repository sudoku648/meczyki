<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Controller\Traits\DataTableTrait;
use App\DataTable\DataTable;
use App\DataTable\DataTableTeamRow;
use App\Entity\Team;
use App\PageView\TeamPageView;
use App\Repository\Contracts\TeamRepositoryInterface;
use App\Repository\DoctrineTeamRepository;
use App\Security\Voter\TeamVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamListController extends TeamAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::LIST);

        return $this->render('team/list.html.twig');
    }

    public function fetch(
        TeamRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(TeamVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new TeamPageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrineTeamRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrineTeamRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->nameLike     = $params['searches']['name'];
        $criteria->clubNameLike = $params['searches']['club'];

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var Team $team */
        foreach ($objects as $objKey => $team) {
            $rows[] = new DataTableTeamRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'team/_datatable_checkbox.html.twig',
                    [
                        'teamId' => $team->getId(),
                    ]
                ),
                $team->getFullName(),
                $team->getClub()->getName(),
                $this->getButtonsForDataTable($team)
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

    private function getButtonsForDataTable(Team $team): string
    {
        $buttons = '';

        if ($this->isGranted(TeamVoter::SHOW, $team)) {
            $buttons .= $this->renderView(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'team_single',
                    'parameters' => [
                        'club_id' => $team->getClub()->getId(),
                        'team_id' => $team->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(TeamVoter::EDIT, $team)) {
            $buttons .= $this->renderView(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'team_edit',
                    'parameters' => [
                        'club_id' => $team->getClub()->getId(),
                        'team_id' => $team->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(TeamVoter::DELETE, $team)) {
            $buttons .= $this->renderView(
                'team/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'club_id'  => $team->getClub()->getId(),
                    'team'     => $team,
                ]
            );
        }

        return $buttons;
    }
}
