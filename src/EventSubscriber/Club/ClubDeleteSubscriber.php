<?php

declare(strict_types=1);

namespace App\EventSubscriber\Club;

use App\Event\Club\ClubDeletedEvent;
use App\Message\Flash\Club\ClubDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ClubDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClubDeletedEvent::class => 'onClubDeleted',
        ];
    }

    public function onClubDeleted(ClubDeletedEvent $event): void
    {
        $this->bus->dispatch(new ClubDeletedFlashMessage($event->getClub()->getId()));
    }
}
