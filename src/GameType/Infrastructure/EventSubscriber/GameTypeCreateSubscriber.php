<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameTypeCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            GameTypeCreatedEvent::class => 'onGameTypeCreated',
        ];
    }

    public function onGameTypeCreated(GameTypeCreatedEvent $event): void
    {
    }
}
