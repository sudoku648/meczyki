<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class TeamSingleController extends TeamAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        #[MapEntity(mapping: ['team_id' => 'id'])] Team $team,
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::SHOW, $team);

        $this->breadcrumbs->addItem(
            $team->getName(),
            $this->router->generate(
                'team_single',
                [
                    'club_id' => $club->getId(),
                    'team_id' => $club->getId(),
                ]
            ),
            [],
            false
        );

        return $this->render(
            'team/single.html.twig',
            [
                'team' => $team,
            ]
        );
    }
}
