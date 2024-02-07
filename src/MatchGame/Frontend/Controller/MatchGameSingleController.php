<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameHasBeenSeenEvent;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
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
