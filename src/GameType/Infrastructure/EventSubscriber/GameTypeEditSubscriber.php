<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameTypeEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            GameTypeUpdatedEvent::class => 'onGameTypeUpdated',
        ];
    }

    public function onGameTypeUpdated(GameTypeUpdatedEvent $event): void
    {
    }
}
