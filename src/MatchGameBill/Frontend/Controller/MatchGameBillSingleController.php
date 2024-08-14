<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameBillVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

final class MatchGameBillSingleController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::SHOW, $matchGameBill);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('match_games_list')
            ->add('match_game_single', ['match_game_id' => $matchGame->getId()])
            ->add('match_game_bill_single', [
                'match_game_id'      => $matchGame->getId(),
                'match_game_bill_id' => $matchGameBill->getId(),
            ]);

        return $this->render(
            'match_game_bill/single.html.twig',
            [
                'matchGameBill' => $matchGameBill,
            ]
        );
    }
}
