<?php

declare(strict_types=1);

namespace App\EventSubscriber\GameType;

use App\Event\GameType\GameTypeDeletedEvent;
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
