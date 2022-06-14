<?php

declare(strict_types=1);

namespace App\EventSubscriber\Team;

use App\Event\Team\TeamDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TeamDeletedEvent::class => 'onTeamDeleted',
        ];
    }

    public function onTeamDeleted(TeamDeletedEvent $event): void
    {
    }
}
