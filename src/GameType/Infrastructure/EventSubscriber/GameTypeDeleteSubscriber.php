<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameTypeDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            GameTypeDeletedEvent::class => 'onGameTypeDeleted',
        ];
    }

    public function onGameTypeDeleted(GameTypeDeletedEvent $event): void
    {
    }
}
