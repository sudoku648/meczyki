<?php

declare(strict_types=1);

namespace App\EventSubscriber\Person;

use App\Event\Person\PersonDeletedEvent;
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
