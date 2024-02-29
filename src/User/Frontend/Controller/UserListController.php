<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\User\Frontend\DataTable\Provider\UserDataTableProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserListController extends UserAbstractController
{
    public function list(): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::LIST);

        return $this->render('user/list.html.twig');
    }

    public function fetch(UserDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }
}
