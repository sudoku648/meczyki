<?php

declare(strict_types=1);

namespace App\EventSubscriber\Club;

use App\Event\Club\ClubCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClubCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ClubCreatedEvent::class => 'onClubCreated',
        ];
    }

    public function onClubCreated(ClubCreatedEvent $event): void
    {
    }
}
