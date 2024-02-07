<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class GameTypeAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected GameTypeManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Panel',
            $this->router->generate('dashboard')
        );
        $this->breadcrumbs->addItem(
            'Typy rozgrywek',
            $this->router->generate('game_types_list')
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
}
