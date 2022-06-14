<?php

declare(strict_types=1);

namespace App\EventSubscriber\GameType;

use App\Event\GameType\GameTypeUpdatedEvent;
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
