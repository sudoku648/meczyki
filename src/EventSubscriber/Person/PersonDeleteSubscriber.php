<?php

declare(strict_types=1);

namespace App\EventSubscriber\Person;

use App\Event\Person\PersonDeletedEvent;
use App\Message\Flash\Person\PersonDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PersonDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PersonDeletedEvent::class => 'onPersonDeleted',
        ];
    }

    public function onPersonDeleted(PersonDeletedEvent $event): void
    {
        $this->bus->dispatch(new PersonDeletedFlashMessage($event->getPerson()->getId()));
    }
}
