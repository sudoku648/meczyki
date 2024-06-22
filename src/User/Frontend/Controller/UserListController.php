<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Sudoku648\Meczyki\User\Frontend\DataTable\Provider\UserDataTableProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserListController extends AbstractController
{
    public function list(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('users_list');

        return $this->render('user/list.html.twig');
    }

    public function fetch(UserDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
