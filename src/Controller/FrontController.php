<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\Voter\FrontVoter;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends AbstractFrontController
{
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted(FrontVoter::FRONT);

        return $this->render(
            'front/front.html.twig',
            []
        );
    }
}
