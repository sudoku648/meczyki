<?php

declare(strict_types=1);

namespace App\EventSubscriber\Team;

use App\Event\Team\TeamHasBeenSeenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamShowSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TeamHasBeenSeenEvent::class => 'onTeamSeen',
        ];
    }

    public function onTeamSeen(TeamHasBeenSeenEvent $event): void
    {
    }
}
