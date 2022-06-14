<?php

declare(strict_types=1);

namespace App\EventSubscriber\Person;

use App\Event\Person\PersonCreatedEvent;
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
