<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Entity\MatchGame;
use App\Event\MatchGame\MatchGameHasBeenSeenEvent;
use App\Security\Voter\MatchGameVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class MatchGameSingleController extends MatchGameAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameVoter::SHOW, $matchGame);

        $this->breadcrumbs->addItem(
            $matchGame->getCompetitors(),
            $this->router->generate(
                'match_game_single',
                [
                    'match_game_id' => $matchGame->getId(),
                ]
            ),
            [],
            false
        );

        $this->dispatcher->dispatch((new MatchGameHasBeenSeenEvent($matchGame)));

        return $this->render(
            'match_game/single.html.twig',
            [
                'matchGame' => $matchGame,
            ]
        );
    }
}