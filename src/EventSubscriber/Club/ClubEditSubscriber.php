<?php

declare(strict_types=1);

namespace App\EventSubscriber\Club;

use App\Event\Club\ClubUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClubEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ClubUpdatedEvent::class => 'onClubUpdated',
        ];
    }

    public function onClubUpdated(ClubUpdatedEvent $event): void
    {
    }
}
