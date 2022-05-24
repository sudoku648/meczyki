<?php

declare(strict_types=1);

namespace App\Controller\Person\Delegate;

use App\Security\Voter\DelegateVoter;
use Symfony\Component\HttpFoundation\Response;

class DelegateFrontController extends DelegateAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(DelegateVoter::LIST);

        return $this->render(
            'person/delegate/index.html.twig',
            []
        );
    }
}
