<?php

declare(strict_types=1);

namespace App\Controller\Person\RefereeObserver;

use App\Controller\AbstractController;
use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RefereeObserverAjaxController extends AbstractController
{
    public function fetchRefereeObservers(
        PersonRepository $repository,
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
            $start, $length, $orders, $search, $columns, 'person.isRefereeObserver = 1'
        );

        $objects = $results['results'];
        $totalObjectsCount = $repository->count(['isRefereeObserver' => true,]);
        $selectedObjectsCount = \count($objects);
        $filteredObjectsCount = $results['countResult'];

        $response = '{
            "draw": '.$draw.',
            "recordsTotal": '.$totalObjectsCount.',
            "recordsFiltered": '.$filteredObjectsCount.',
            "data": [';

        $i = 0;

        /** @var Person $person */
        foreach ($objects as $objKey => $person) {
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
                        $responseTemp = "<input type='checkbox' id='checkbox_person_".$person->getId()."' ";
                        $responseTemp .= "class='form-check-input' data-personId='".$person->getId()."'>";
                        break;
                    }
                    case 'fullName':
                    {
                        $responseTemp = $person->getFullName();
                        break;
                    }
                    case 'buttons':
                    {
                        $responseTemp = '';

                        if ($this->isGranted(PersonVoter::SHOW, $person)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/show.html.twig',
                                    [
                                        'path' => 'person_single',
                                        'parameters' =>
                                        [
                                            'person_id' => $person->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(PersonVoter::EDIT, $person)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'buttons/edit.html.twig',
                                    [
                                        'path' => 'person_edit',
                                        'parameters' =>
                                        [
                                            'person_id' => $person->getId(),
                                        ],
                                    ]
                                )
                            );
                        }
                        if ($this->isGranted(PersonVoter::DELETE, $person)) {
                            $responseTemp .= \str_replace(
                                ["\r\n", "\n", "\r", '"'],
                                [' ', ' ', ' ', "'"],
                                $this->renderView(
                                    'person/_delete_form.html.twig',
                                    [
                                        'person' => $person,
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