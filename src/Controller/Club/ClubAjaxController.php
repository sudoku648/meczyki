<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Controller\AbstractController;
use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Security\Voter\ClubVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ClubAjaxController extends AbstractController
{
    public function fetchClubs(
        ClubRepository $repository,
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

        /** @var Club $club */
        foreach ($objects as $objKey => $club) {
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
                    case 'name':
                    {
                        $responseTemp = $club->getName();
                        break;
                    }
                    case 'buttons':
                    {
                        $responseTemp = '';

                        if ($this->isGranted(ClubVoter::SHOW, $club)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/show.html.twig',
                                    [
                                        'path' => 'club_single',
                                        'parameters' =>
                                        [
                                            'club_id' => $club->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(ClubVoter::EDIT, $club)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/edit.html.twig',
                                    [
                                        'path' => 'club_edit',
                                        'parameters' =>
                                        [
                                            'club_id' => $club->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(ClubVoter::DELETE, $club)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'club/_delete_form.html.twig',
                                    [
                                        'club' => $club,
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