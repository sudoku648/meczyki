<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Controller\AbstractController;
use App\Entity\Team;
use App\Service\TeamManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class TeamAbstractController extends AbstractController
{
    protected TeamManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        TeamManager $manager,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher,
        Breadcrumbs $breadcrumbs
    )
    {
        parent::__construct(
            $router,
            $dispatcher,
            $breadcrumbs
        );

        $this->manager = $manager;

        $this->breadcrumbs->addItem(
            'Drużyny',
            $this->router->generate('teams_front')
        );
    }

    protected function redirectToTeamsList(): Response
    {
        return $this->redirectToRoute(
            'teams_front',
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
