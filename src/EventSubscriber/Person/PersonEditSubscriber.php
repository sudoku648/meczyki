<?php

declare(strict_types=1);

namespace App\EventSubscriber\Person;

use App\Event\Person\PersonPersonalInfoUpdatedEvent;
use App\Event\Person\PersonUpdatedEvent;
use App\Message\Flash\Person\PersonPersonalInfoUpdatedFlashMessage;
use App\Message\Flash\Person\PersonUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PersonEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PersonUpdatedEvent::class => 'onPersonUpdated',
            PersonPersonalInfoUpdatedEvent::class => 'onPersonPersonalInfoUpdated',
        ];
    }

    public function onPersonUpdated(PersonUpdatedEvent $event): void
    {
        $this->bus->dispatch(new PersonUpdatedFlashMessage($event->getPerson()->getId()));
    }

    public function onPersonPersonalInfoUpdated(PersonPersonalInfoUpdatedEvent $event): void
    {
        $this->bus->dispatch(new PersonPersonalInfoUpdatedFlashMessage($event->getPerson()->getId()));
    }
}
