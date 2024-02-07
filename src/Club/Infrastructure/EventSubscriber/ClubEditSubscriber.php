<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Club\Domain\Event\ClubUpdatedEvent;
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
