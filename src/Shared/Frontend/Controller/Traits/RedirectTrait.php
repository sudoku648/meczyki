<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Controller\Traits;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\User\Domain\Entity\User;
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

    protected function redirectToPeopleList(): Response
    {
        return $this->redirectToRoute(
            'people_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditPerson(Person $person): Response
    {
        return $this->redirectToRoute(
            'person_edit',
            [
                'person_id' => $person->getId(),
            ]
        );
    }

    protected function redirectToEditPersonalInfo(): Response
    {
        return $this->redirectToRoute('person_personal_info_edit');
    }

    protected function redirectToTeamsList(): Response
    {
        return $this->redirectToRoute(
            'teams_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditTeam(Team $team): Response
    {
        return $this->redirectToRoute(
            'team_edit',
            [
                'club_id' => $team->getClub()->getId(),
                'team_id' => $team->getId(),
            ]
        );
    }

    protected function redirectToUsersList(): Response
    {
        return $this->redirectToRoute(
            'users_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditUser(User $user): Response
    {
        return $this->redirectToRoute(
            'user_edit',
            [
                'user_id' => $user->getId(),
            ]
        );
    }

    protected function redirectToUserBindWithPerson(User $user): Response
    {
        return $this->redirectToRoute(
            'user_bind_with_person',
            [
                'user_id' => $user->getId(),
            ]
        );
    }
}
