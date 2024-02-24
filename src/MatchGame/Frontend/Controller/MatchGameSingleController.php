<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class MatchGameSingleController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameVoter::SHOW, $matchGame);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('match_games_list')
            ->add('match_game_single', ['match_game_id' => $matchGame->getId()]);

        return $this->render(
            'match_game/single.html.twig',
            [
                'matchGame' => $matchGame,
            ]
        );
    }
}
