<?php

declare(strict_types=1);

namespace App\EventSubscriber\Club;

use App\Event\Club\ClubDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClubDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ClubDeletedEvent::class => 'onClubDeleted',
        ];
    }

    public function onClubDeleted(ClubDeletedEvent $event): void
    {
    }
}
