<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Controller\AbstractController;
use App\Entity\MatchGame;
use App\Service\MatchGameManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class MatchGameAbstractController extends AbstractController
{
    protected MatchGameManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        MatchGameManager $manager,
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
            'Mecze',
            $this->router->generate('match_games_front')
        );
    }

    protected function redirectToMatchGamesList(): Response
    {
        return $this->redirectToRoute(
            'match_games_front',
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
}
