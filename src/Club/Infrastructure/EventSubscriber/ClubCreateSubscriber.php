<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Club\Domain\Event\ClubCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClubCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ClubCreatedEvent::class => 'onClubCreated',
        ];
    }

    public function onClubCreated(ClubCreatedEvent $event): void
    {
    }
}
