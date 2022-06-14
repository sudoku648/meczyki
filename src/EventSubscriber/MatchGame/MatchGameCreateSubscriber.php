<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGame;

use App\Event\MatchGame\MatchGameCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameCreatedEvent::class => 'onMatchGameCreated',
        ];
    }

    public function onMatchGameCreated(MatchGameCreatedEvent $event): void
    {
    }
}
