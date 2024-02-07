<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Person\Domain\Event\PersonDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PersonDeletedEvent::class => 'onPersonDeleted',
        ];
    }

    public function onPersonDeleted(PersonDeletedEvent $event): void
    {
    }
}
