<?php

declare(strict_types=1);

namespace App\EventSubscriber\Club;

use App\Event\Club\ClubCreatedEvent;
use App\Message\Flash\Club\ClubCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ClubCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClubCreatedEvent::class => 'onClubCreated',
        ];
    }

    public function onClubCreated(ClubCreatedEvent $event): void
    {
        $this->bus->dispatch(new ClubCreatedFlashMessage($event->getClub()->getId()));
    }
}
