<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Security\Voter\UserRoleVoter;
use Symfony\Component\HttpFoundation\Response;

class UserRoleFrontController extends UserRoleAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(UserRoleVoter::LIST);

        return $this->render(
            'user_role/index.html.twig',
            []
        );
    }
}
