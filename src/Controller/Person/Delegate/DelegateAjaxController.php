<?php

declare(strict_types=1);

namespace App\Controller\Person\Delegate;

use App\Controller\AbstractController;
use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DelegateAjaxController extends AbstractController
{
    public function fetchDelegates(
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
            $start, $length, $orders, $search, $columns, 'person.isDelegate = 1'
        );

        $objects = $results['results'];
        $totalObjectsCount = $repository->count(['isDelegate' => true,]);
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
                        $responseTemp = '<input type="checkbox" id="checkbox_person_'.$person->getId().'" ';
                        $responseTemp .= 'class="form-check-input" data-personId="'.$person->getId().'">';
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
                            $responseTemp .= $this->renderView(
                                'buttons/show.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'person_single',
                                    'parameters' =>
                                    [
                                        'person_id' => $person->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(PersonVoter::EDIT, $person)) {
                            $responseTemp .= $this->renderView(
                                'buttons/edit.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'person_edit',
                                    'parameters' =>
                                    [
                                        'person_id' => $person->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(PersonVoter::DELETE, $person)) {
                            $responseTemp .= $this->renderView(
                                'person/_delete_form.html.twig',
                                [
                                    'btn_size' => 'table',
                                    'person'   => $person,
                                ]
                            );
                        }
                        break;
                    }
                }

                $responseTemp = $this->escapeDTResponse($responseTemp);

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