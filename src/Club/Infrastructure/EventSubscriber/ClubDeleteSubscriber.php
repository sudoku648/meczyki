<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Club\Domain\Event\ClubDeletedEvent;
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
