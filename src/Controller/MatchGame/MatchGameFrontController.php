<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Security\Voter\MatchGameVoter;
use Symfony\Component\HttpFoundation\Response;

class MatchGameFrontController extends MatchGameAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::LIST);

        return $this->render(
            'match_game/index.html.twig',
            []
        );
    }
}
