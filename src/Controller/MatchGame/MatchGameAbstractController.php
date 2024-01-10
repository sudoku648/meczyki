<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Controller\AbstractController;
use App\Entity\MatchGame;
use App\Service\Contracts\MatchGameManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class MatchGameAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected MatchGameManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Mecze',
            $this->router->generate('match_games_list')
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
}
