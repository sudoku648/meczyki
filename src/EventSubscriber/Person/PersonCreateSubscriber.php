<?php

declare(strict_types=1);

namespace App\EventSubscriber\Person;

use App\Event\Person\PersonCreatedEvent;
use App\Message\Flash\Person\PersonCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PersonCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PersonCreatedEvent::class => 'onPersonCreated',
        ];
    }

    public function onPersonCreated(PersonCreatedEvent $event): void
    {
        $this->bus->dispatch(new PersonCreatedFlashMessage($event->getPerson()->getId()));
    }
}
