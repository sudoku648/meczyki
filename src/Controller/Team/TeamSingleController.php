<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Club;
use App\Entity\Team;
use App\Event\Team\TeamHasBeenSeenEvent;
use App\Security\Voter\TeamVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class TeamSingleController extends TeamAbstractController
{
    #[ParamConverter('club', options: ['mapping' => ['club_id' => 'id']])]
    #[ParamConverter('team', options: ['mapping' => ['team_id' => 'id']])]
    public function __invoke(Club $club, Team $team): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::SHOW, $team);

        $this->breadcrumbs->addItem(
            $team->getFullName(),
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

        $this->dispatcher->dispatch((new TeamHasBeenSeenEvent($team)));

        return $this->render(
            'team/single.html.twig',
            [
                'team' => $team,
            ]
        );
    }
}
