<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGame;

use App\Event\MatchGame\MatchGameUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameUpdatedEvent::class => 'onMatchGameUpdated',
        ];
    }

    public function onMatchGameUpdated(MatchGameUpdatedEvent $event): void
    {
    }
}
