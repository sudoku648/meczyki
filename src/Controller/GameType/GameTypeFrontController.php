<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Security\Voter\GameTypeVoter;
use Symfony\Component\HttpFoundation\Response;

class GameTypeFrontController extends GameTypeAbstractController
{
    public function front(): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::LIST);

        return $this->render(
            'game_type/index.html.twig',
            []
        );
    }
}
