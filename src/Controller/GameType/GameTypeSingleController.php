<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
use App\Event\GameType\GameTypeHasBeenSeenEvent;
use App\Security\Voter\GameTypeVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class GameTypeSingleController extends GameTypeAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType
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