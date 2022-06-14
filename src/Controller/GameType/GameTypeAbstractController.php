<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Controller\AbstractController;
use App\Entity\GameType;
use App\Service\GameTypeManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class GameTypeAbstractController extends AbstractController
{
    protected GameTypeManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        GameTypeManager $manager,
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
            'Typy rozgrywek',
            $this->router->generate('game_types_front')
        );
    }

    protected function redirectToGameTypesList(): Response
    {
        return $this->redirectToRoute(
            'game_types_front',
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
}
