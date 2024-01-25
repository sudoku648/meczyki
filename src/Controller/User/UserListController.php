<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\Traits\DataTableTrait;
use App\DataTable\DataTable;
use App\DataTable\DataTableUserRow;
use App\Entity\User;
use App\PageView\UserPageView;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\DoctrineUserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserListController extends UserAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::LIST);

        return $this->render('user/list.html.twig');
    }

    public function fetch(
        UserRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(UserVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new UserPageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrineUserRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrineUserRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->usernameLike = $params['searches']['username'];

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var User $user */
        foreach ($objects as $objKey => $user) {
            $rows[] = new DataTableUserRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'user/_datatable_checkbox.html.twig',
                    [
                        'userId' => $user->getId(),
                    ]
                ),
                $user->getUsername()->getValue(),
                $this->getButtonsForDataTable($user)
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

    private function getButtonsForDataTable(User $user): string
    {
        $buttons = '';

        if ($this->isGranted(UserVoter::SHOW, $user)) {
            $buttons .= $this->renderView(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'user_single',
                    'parameters' => [
                        'user_id' => $user->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(UserVoter::EDIT, $user)) {
            $buttons .= $this->renderView(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'user_edit',
                    'parameters' => [
                        'user_id' => $user->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(UserVoter::ACTIVATE, $user)) {
            $buttons .= $this->renderView(
                'user/_activate_form.html.twig',
                [
                    'btn_size' => 'table',
                    'user'     => $user,
                ]
            );
        } elseif ($this->isGranted(UserVoter::DEACTIVATE, $user)) {
            $buttons .= $this->renderView(
                'user/_deactivate_form.html.twig',
                [
                    'btn_size' => 'table',
                    'user'     => $user,
                ]
            );
        }
        if ($this->isGranted(UserVoter::BIND_WITH_PERSON, $user)) {
            $buttons .= $this->renderView(
                'user/_buttons/bind_with_person.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'user_bind_with_person',
                    'parameters' => [
                        'user_id' => $user->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(UserVoter::IMPERSONATE, $user)) {
            $buttons .= $this->renderView(
                'user/_buttons/impersonate.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'dashboard',
                    'parameters' => [
                        '_switch_user' => $user->getUserIdentifier(),
                    ],
                ]
            );
        }
        if ($this->isGranted(UserVoter::DELETE, $user)) {
            $buttons .= $this->renderView(
                'user/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'user'     => $user,
                ]
            );
        }

        return $buttons;
    }
}
