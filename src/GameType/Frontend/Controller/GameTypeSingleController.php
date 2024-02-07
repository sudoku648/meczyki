<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeHasBeenSeenEvent;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class GameTypeSingleController extends GameTypeAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType,
    ): Response {
        $this->denyAccessUnlessGranted(GameTypeVoter::SHOW, $gameType);

        $this->breadcrumbs->addItem(
            $gameType->getName(),
            $this->router->generate(
                'game_type_single',
                [
                    'game_type_id' => $gameType->getId(),
                ]
            )
        );

        $this->dispatcher->dispatch((new GameTypeHasBeenSeenEvent($gameType)));

        return $this->render(
            'game_type/single.html.twig',
            [
                'gameType' => $gameType,
            ]
        );
    }
}
