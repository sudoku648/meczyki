<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Controller\AbstractController;
use App\Entity\Team;
use App\Service\Contracts\TeamManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class TeamAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected TeamManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Drużyny',
            $this->router->generate('teams_list')
        );
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
}
