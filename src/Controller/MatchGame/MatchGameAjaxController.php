<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Controller\AbstractController;
use App\Entity\MatchGame;
use App\Repository\MatchGameRepository;
use App\Security\Voter\MatchGameVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MatchGameAjaxController extends AbstractController
{
    public function fetchMatchGames(
        MatchGameRepository $repository,
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

        /** @var MatchGame $matchGame */
        foreach ($objects as $objKey => $matchGame) {
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
                        $responseTemp = "<input type='checkbox' id='checkbox_matchGame_".$matchGame->getId()."' ";
                        $responseTemp .= "class='form-check-input' data-matchGameId='".$matchGame->getId()."'>";
                        break;
                    }
                    case 'dateTime':
                    {
                        $responseTemp = $matchGame->getDateTime()->format('d.m.Y, H:i');
                        break;
                    }
                    case 'gameType':
                    {
                        $responseTemp = $matchGame->getGameType()
                            ? $matchGame->getGameType()->getFullName() : "<em class='text-black-50'>nieznany</em>";
                        break;
                    }
                    case 'teams':
                    {
                        switch (true) {
                            case $matchGame->getHomeTeam() && $matchGame->getAwayTeam():
                                $responseTemp =
                                    $matchGame->getHomeTeam()->getFullName().
                                    ' - '.
                                    $matchGame->getAwayTeam()->getFullName();
                                break;
                            case $matchGame->getHomeTeam():
                                $responseTemp =
                                    $matchGame->getHomeTeam()->getFullName().
                                    ' - '.
                                    "<em class='text-black-50'>nieznany</em>";
                                break;
                            case $matchGame->getAwayTeam():
                                $responseTemp =
                                    "<em class='text-black-50'>nieznany</em>".
                                    ' - '.
                                    $matchGame->getAwayTeam()->getFullName();
                                break;
                            default:
                                $responseTemp = "<em class='text-black-50'>nieznane</em>";
                        }
                        break;
                    }
                    case 'buttons':
                    {
                        $responseTemp = '';

                        if ($this->isGranted(MatchGameVoter::SHOW, $matchGame)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/show.html.twig',
                                    [
                                        'btn_size'   => 'table',
                                        'path'       => 'match_game_single',
                                        'parameters' =>
                                        [
                                            'match_game_id' => $matchGame->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(MatchGameVoter::EDIT, $matchGame)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/edit.html.twig',
                                    [
                                        'btn_size'   => 'table',
                                        'path'       => 'match_game_edit',
                                        'parameters' =>
                                        [
                                            'match_game_id' => $matchGame->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(MatchGameVoter::DELETE, $matchGame)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'match_game/_delete_form.html.twig',
                                    [
                                        'btn_size'  => 'table',
                                        'matchGame' => $matchGame,
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