<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Person\Domain\Event\PersonPersonalInfoUpdatedEvent;
use Sudoku648\Meczyki\Person\Domain\Event\PersonUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PersonUpdatedEvent::class             => 'onPersonUpdated',
            PersonPersonalInfoUpdatedEvent::class => 'onPersonPersonalInfoUpdated',
        ];
    }

    public function onPersonUpdated(PersonUpdatedEvent $event): void
    {
    }

    public function onPersonPersonalInfoUpdated(PersonPersonalInfoUpdatedEvent $event): void
    {
    }
}
