<?php

declare(strict_types=1);

namespace App\EventSubscriber\Team;

use App\Event\Team\TeamUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TeamUpdatedEvent::class => 'onTeamUpdated',
        ];
    }

    public function onTeamUpdated(TeamUpdatedEvent $event): void
    {
    }
}
