<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Security\Voter\TeamVoter;
use Symfony\Component\HttpFoundation\Response;

class TeamFrontController extends TeamAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::LIST);

        return $this->render(
            'team/index.html.twig',
            []
        );
    }
}
