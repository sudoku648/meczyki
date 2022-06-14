<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Controller\AbstractController;
use App\Entity\Club;
use App\Service\ClubManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ClubAbstractController extends AbstractController
{
    protected ClubManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        ClubManager $manager,
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
            'Kluby',
            $this->router->generate('clubs_front')
        );
    }

    protected function redirectToClubsList(): Response
    {
        return $this->redirectToRoute(
            'clubs_front',
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
}
