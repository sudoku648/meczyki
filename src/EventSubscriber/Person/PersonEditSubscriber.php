<?php

declare(strict_types=1);

namespace App\EventSubscriber\Person;

use App\Event\Person\PersonPersonalInfoUpdatedEvent;
use App\Event\Person\PersonUpdatedEvent;
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
