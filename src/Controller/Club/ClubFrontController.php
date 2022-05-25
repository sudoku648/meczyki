<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Security\Voter\ClubVoter;
use Symfony\Component\HttpFoundation\Response;

class ClubFrontController extends ClubAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::LIST);

        return $this->render(
            'club/index.html.twig',
            []
        );
    }
}
