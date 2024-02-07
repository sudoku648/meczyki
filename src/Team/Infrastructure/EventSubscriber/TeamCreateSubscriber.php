<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Team\Domain\Event\TeamCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TeamCreatedEvent::class => 'onTeamCreated',
        ];
    }

    public function onTeamCreated(TeamCreatedEvent $event): void
    {
    }
}
