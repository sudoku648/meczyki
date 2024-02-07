<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Team\Domain\Event\TeamHasBeenSeenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamShowSubscriber implements EventSubscriberInterface
{
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
