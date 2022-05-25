<?php

declare(strict_types=1);

namespace App\EventSubscriber\Club;

use App\Event\Club\ClubUpdatedEvent;
use App\Message\Flash\Club\ClubUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ClubEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClubUpdatedEvent::class => 'onClubUpdated',
        ];
    }

    public function onClubUpdated(ClubUpdatedEvent $event): void
    {
        $this->bus->dispatch(new ClubUpdatedFlashMessage($event->getClub()->getId()));
    }
}
