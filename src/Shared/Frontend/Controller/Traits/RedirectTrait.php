<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Controller\Traits;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Symfony\Component\HttpFoundation\Response;

trait RedirectTrait
{
    protected function redirectToClubsList(): Response
    {
        return $this->redirectToRoute(
            'clubs_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditClub(Club $club): Response
    {
        return $this->redirectToRoute(
            'club_edit',
            [
                'club_id' => $club->getId(),
            ]
        );
    }

    protected function redirectToGameTypesList(): Response
    {
        return $this->redirectToRoute(
            'game_types_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditGameType(GameType $gameType): Response
    {
        return $this->redirectToRoute(
            'game_type_edit',
            [
                'game_type_id' => $gameType->getId(),
            ]
        );
    }

    protected function redirectToMatchGamesList(): Response
    {
        return $this->redirectToRoute(
            'match_games_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditMatchGame(MatchGame $matchGame): Response
    {
        return $this->redirectToRoute(
            'match_game_edit',
            [
                'match_game_id' => $matchGame->getId(),
            ]
        );
    }

    protected function redirectToEditBill(MatchGameBill $matchGameBill): Response
    {
        return $this->redirectToRoute(
            'match_game_bill_edit',
            [
                'match_game_id'      => $matchGameBill->getMatchGame()->getId(),
                'match_game_bill_id' => $matchGameBill->getId(),
            ]
        );
    }
}
