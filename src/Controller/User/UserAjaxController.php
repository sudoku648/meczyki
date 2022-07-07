<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserAjaxController extends UserAbstractController
{
    public function fetchUsers(
        UserRepository $repository,
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

        /** @var User $user */
        foreach ($objects as $objKey => $user) {
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
                        $responseTemp = $this->renderView(
                            'user/_datatable_checkbox.html.twig',
                            [
                                'userId' => $user->getId(),
                            ]
                        );
                        break;
                    }
                    case 'username':
                    {
                        $responseTemp = $user->getUsername();
                        break;
                    }
                    case 'buttons':
                    {
                        $responseTemp = '';

                        if ($this->isGranted(UserVoter::SHOW, $user)) {
                            $responseTemp .= $this->renderView(
                                'buttons/show.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'user_single',
                                    'parameters' =>
                                    [
                                        'user_id' => $user->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(UserVoter::EDIT, $user)) {
                            $responseTemp .= $this->renderView(
                                'buttons/edit.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'user_edit',
                                    'parameters' =>
                                    [
                                        'user_id' => $user->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(UserVoter::ACTIVATE, $user)) {
                            $responseTemp .= $this->renderView(
                                'user/_activate_form.html.twig',
                                [
                                    'btn_size' => 'table',
                                    'user'     => $user,
                                ]
                            );
                        } elseif ($this->isGranted(UserVoter::DEACTIVATE, $user)) {
                            $responseTemp .= $this->renderView(
                                'user/_deactivate_form.html.twig',
                                [
                                    'btn_size' => 'table',
                                    'user'     => $user,
                                ]
                            );
                        }
                        if ($this->isGranted(UserVoter::BIND_WITH_PERSON, $user)) {
                            $responseTemp .= $this->renderView(
                                'user/_buttons/bind_with_person.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'user_bind_with_person',
                                    'parameters' =>
                                    [
                                        'user_id' => $user->getId(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(UserVoter::IMPERSONATE, $user)) {
                            $responseTemp .= $this->renderView(
                                'user/_buttons/impersonate.html.twig',
                                [
                                    'btn_size'   => 'table',
                                    'path'       => 'front',
                                    'parameters' =>
                                    [
                                        '_switch_user' => $user->getUserIdentifier(),
                                    ],
                                ]
                            );
                        }
                        if ($this->isGranted(UserVoter::DELETE, $user)) {
                            $responseTemp .= $this->renderView(
                                'user/_delete_form.html.twig',
                                [
                                    'btn_size' => 'table',
                                    'user'     => $user,
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
