<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Team\Domain\Event\TeamUpdatedEvent;
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
