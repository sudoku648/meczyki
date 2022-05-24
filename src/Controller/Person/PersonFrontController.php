<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\Response;

class PersonFrontController extends PersonAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        return $this->render(
            'person/index.html.twig',
            []
        );
    }
}
