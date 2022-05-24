<?php

declare(strict_types=1);

namespace App\Controller\Person\RefereeObserver;

use App\Security\Voter\RefereeObserverVoter;
use Symfony\Component\HttpFoundation\Response;

class RefereeObserverFrontController extends RefereeObserverAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(RefereeObserverVoter::LIST);

        return $this->render(
            'person/referee_observer/index.html.twig',
            []
        );
    }
}
