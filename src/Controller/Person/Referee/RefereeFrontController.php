<?php

declare(strict_types=1);

namespace App\Controller\Person\Referee;

use App\Security\Voter\RefereeVoter;
use Symfony\Component\HttpFoundation\Response;

class RefereeFrontController extends RefereeAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(RefereeVoter::LIST);

        return $this->render(
            'person/referee/index.html.twig',
            []
        );
    }
}
