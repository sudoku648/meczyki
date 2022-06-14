<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGame;

use App\Event\MatchGame\MatchGameDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameDeletedEvent::class => 'onMatchGameDeleted',
        ];
    }

    public function onMatchGameDeleted(MatchGameDeletedEvent $event): void
    {
    }
}
