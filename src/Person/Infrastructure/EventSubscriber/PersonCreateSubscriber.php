<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\Person\Domain\Event\PersonCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PersonCreatedEvent::class => 'onPersonCreated',
        ];
    }

    public function onPersonCreated(PersonCreatedEvent $event): void
    {
    }
}
