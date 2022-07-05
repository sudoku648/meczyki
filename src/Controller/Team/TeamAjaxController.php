<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Security\Voter\TeamVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamAjaxController extends TeamAbstractController
{
    public function fetchTeams(
        TeamRepository $repository,
        Request $request
    ): JsonResponse
    {
        foreach ($request->request->all() as $key => $param) {
            switch ($key) {
                case 'draw':    $draw    = $param; break;
                case 'columns': $columns = $param; break;
                case 'order':   $orders  = $param; break;
                case 'start':   $start   = $param; break;
                case 'length':  $length  = $param; break;
                case 'search':  $search  = $param; break;
            }
        }

        foreach ($orders as $key => $order) {
            $orders[$key]['name'] = $columns[$order['column']]['name'];
        }

        $results = $repository->getRequiredDTData(
            $start, $length, $orders, $search, $columns
        );

        $objects = $results['results'];
        $totalObjectsCount = $repository->count([]);
        $selectedObjectsCount = \count($objects);
        $filteredObjectsCount = $results['countResult'];

        $response = '{
            "draw": '.$draw.',
            "recordsTotal": '.$totalObjectsCount.',
            "recordsFiltered": '.$filteredObjectsCount.',
            "data": [';

        $i = 0;

        /** @var Team $team */
        foreach ($objects as $objKey => $team) {
            $response .= '["';

            $j = 0;
            $nbColumn = \count($columns);
            foreach ($columns as $colKey => $column) {
                $responseTemp = '-';

                switch ($column['name']) {
                    case 'lp':
                    {
                        $responseTemp = $objKey + 1 + $start;
                        break;
                    }
                    case 'checkbox':
                    {
                        $responseTemp = "<input type='checkbox' id='checkbox_team_".$team->getId()."' ";
                        $responseTemp .= "class='form-check-input' data-teamId='".$team->getId()."'>";
                        break;
                    }
                    case 'name':
                    {
                        $responseTemp = $team->getFullName();
                        break;
                    }
                    case 'club':
                    {
                        $responseTemp = $team->getClub()->getName();
                        break;
                    }
                    case 'buttons':
                    {
                        $responseTemp = '';

                        if ($this->isGranted(TeamVoter::SHOW, $team)) {
                            $responseTemp = \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/show.html.twig',
                                    [
                                        'path' => 'team_single',
                                        'parameters' =>
                                        [
                                            'club_id' => $team->getClub()->getId(),
                                            'team_id' => $team->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(TeamVoter::EDIT, $team)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/edit.html.twig',
                                    [
                                        'path' => 'team_edit',
                                        'parameters' =>
                                        [
                                            'club_id' => $team->getClub()->getId(),
                                            'team_id' => $team->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(TeamVoter::DELETE, $team)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'team/_delete_form.html.twig',
                                    [
                                        'club_id' => $team->getClub()->getId(),
                                        'team' => $team,
                                    ]
                                )
                            );
                        }
                        break;
                    }
                }

                $response .= $responseTemp;

                if (++$j !== $nbColumn)
                    $response .= '","';
            }

            $response .= '"]';

            if (++$i !== $selectedObjectsCount)
                $response .= ',';
        }

        $response .= ']}';

        $returnResponse = new JsonResponse();
        $returnResponse->setJson($response);
        return $returnResponse;
    }
}