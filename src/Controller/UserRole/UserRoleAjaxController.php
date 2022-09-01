<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Controller\AbstractController;
use App\Entity\UserRole;
use App\Repository\UserRoleRepository;
use App\Security\Voter\UserRoleVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use function count;

class UserRoleAjaxController extends AbstractController
{
    public function fetchUserRoles(
        UserRoleRepository $repository,
        Request $request
    ): JsonResponse {
        foreach ($request->request->all() as $key => $param) {
            switch ($key) {
                case 'draw':    $draw    = $param;

                    break;
                case 'columns': $columns = $param;

                    break;
                case 'order':   $orders  = $param;

                    break;
                case 'start':   $start   = $param;

                    break;
                case 'length':  $length  = $param;

                    break;
                case 'search':  $search  = $param;

                    break;
            }
        }

        foreach ($orders as $key => $order) {
            $orders[$key]['name'] = $columns[$order['column']]['name'];
        }

        $results = $repository->getRequiredDTData(
            $start,
            $length,
            $orders,
            $search,
            $columns
        );

        $objects              = $results['results'];
        $totalObjectsCount    = $repository->count([]);
        $selectedObjectsCount = count($objects);
        $filteredObjectsCount = $results['countResult'];

        $response = '{
            "draw": ' . $draw . ',
            "recordsTotal": ' . $totalObjectsCount . ',
            "recordsFiltered": ' . $filteredObjectsCount . ',
            "data": [';

        $i = 0;

        /** @var UserRole $userRole */
        foreach ($objects as $objKey => $userRole) {
            $response .= '["';

            $j        = 0;
            $nbColumn = count($columns);
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
                                'user_role/_datatable_checkbox.html.twig',
                                [
                                    'userRoleId' => $userRole->getId(),
                                ]
                            );

                            break;
                        }
                    case 'name':
                        {
                            $responseTemp = $userRole->getName();

                            break;
                        }
                    case 'buttons':
                        {
                            $responseTemp = '';

                            if ($this->isGranted(UserRoleVoter::EDIT, $userRole)) {
                                $responseTemp .= $this->renderView(
                                    'buttons/edit.html.twig',
                                    [
                                        'btn_size'   => 'table',
                                        'path'       => 'user_role_edit',
                                        'parameters' => [
                                            'user_role_id' => $userRole->getId(),
                                        ],
                                    ]
                                );
                            }
                            if ($this->isGranted(UserRoleVoter::DELETE, $userRole)) {
                                $responseTemp .= $this->renderView(
                                    'user_role/_delete_form.html.twig',
                                    [
                                        'btn_size' => 'table',
                                        'userRole' => $userRole,
                                    ]
                                );
                            }

                            break;
                        }
                }

                $responseTemp = $this->escapeDTResponse($responseTemp);

                $response .= $responseTemp;

                if (++$j !== $nbColumn) {
                    $response .= '","';
                }
            }

            $response .= '"]';

            if (++$i !== $selectedObjectsCount) {
                $response .= ',';
            }
        }

        $response .= ']}';

        $returnResponse = new JsonResponse();
        $returnResponse->setJson($response);

        return $returnResponse;
    }
}
