<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Security\Voter\UserVoter;
use Symfony\Component\HttpFoundation\Response;

class UserFrontController extends UserAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::LIST);

        return $this->render(
            'user/index.html.twig',
            []
        );
    }
}
