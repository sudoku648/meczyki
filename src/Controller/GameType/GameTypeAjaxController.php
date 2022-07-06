<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Controller\AbstractController;
use App\Entity\GameType;
use App\Repository\GameTypeRepository;
use App\Security\Voter\GameTypeVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameTypeAjaxController extends AbstractController
{
    public function fetchGameTypes(
        GameTypeRepository $repository,
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

        /** @var GameType $gameType */
        foreach ($objects as $objKey => $gameType) {
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
                        $responseTemp = '<input type="checkbox" id="checkbox_gameType_'.$gameType->getId().'" ';
                        $responseTemp .= 'class="form-check-input" data-gameTypeId="'.$gameType->getId().'">';
                        break;
                    }
                    case 'name':
                    {
                        $responseTemp = $gameType->getFullName();
                        break;
                    }
                    case 'buttons':
                    {
                        $responseTemp = '';

                        if ($this->isGranted(GameTypeVoter::SHOW, $gameType)) {
                            $responseTemp .= $this->renderView(
                                'buttons/show.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'game_type_single',
                                    'parameters' =>
                                    [
                                        'game_type_id' => $gameType->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(GameTypeVoter::EDIT, $gameType)) {
                            $responseTemp .= $this->renderView(
                                'buttons/edit.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'game_type_edit',
                                    'parameters' =>
                                    [
                                        'game_type_id' => $gameType->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(GameTypeVoter::DELETE, $gameType)) {
                            $responseTemp .= $this->renderView(
                                'game_type/_delete_form.html.twig',
                                [
                                    'btn_size' => 'table',
                                    'gameType' => $gameType,
                                ]
                            );
                        }
                        break;
                    }
                }

                $responseTemp = \str_replace(
                    ["\r\n", "\n", "\r", '"'],
                    [' ', ' ', ' ', "'"],
                    (string) $responseTemp
                );

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