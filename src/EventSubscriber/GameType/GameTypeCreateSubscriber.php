<?php

declare(strict_types=1);

namespace App\EventSubscriber\GameType;

use App\Event\GameType\GameTypeCreatedEvent;
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
