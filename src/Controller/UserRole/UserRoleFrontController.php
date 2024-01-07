<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Controller\Traits\DataTableTrait;
use App\DataTable\DataTable;
use App\DataTable\DataTableUserRoleRow;
use App\Entity\UserRole;
use App\PageView\UserRolePageView;
use App\Repository\Contracts\UserRoleRepositoryInterface;
use App\Repository\DoctrineUserRoleRepository;
use App\Security\Voter\UserRoleVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleFrontController extends UserRoleAbstractController
{
    use DataTableTrait;

    public function front(): Response
    {
        $this->denyAccessUnlessGranted(UserRoleVoter::LIST);

        return $this->render('user_role/index.html.twig');
    }

    public function fetch(
        UserRoleRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(UserRoleVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new UserRolePageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrineUserRoleRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrineUserRoleRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->nameLike = $params['searches']['name'];

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var UserRole $userRole */
        foreach ($objects as $objKey => $userRole) {
            $rows[] = new DataTableUserRoleRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'user_role/_datatable_checkbox.html.twig',
                    [
                        'userRoleId' => $userRole->getId(),
                    ]
                ),
                $userRole->getName(),
                $this->getButtonsForDataTable($userRole)
            );
        }

        $dataTable = new DataTable(
            $params['draw'],
            $repository->getTotalCount(),
            $repository->countByCriteria($criteria),
            $rows
        );

        return new JsonResponse($dataTable);
    }

    private function getButtonsForDataTable(UserRole $userRole): string
    {
        $buttons = '';

        if ($this->isGranted(UserRoleVoter::EDIT, $userRole)) {
            $buttons .= $this->renderView(
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
            $buttons .= $this->renderView(
                'user_role/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'userRole' => $userRole,
                ]
            );
        }

        return $buttons;
    }
}
