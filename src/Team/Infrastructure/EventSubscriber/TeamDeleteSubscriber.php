<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Team\Domain\Event\TeamDeletedEvent;
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
