<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserDeletedEvent;
use App\Message\Flash\User\UserDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserDeletedEvent::class => 'onUserDeleted',
        ];
    }

    public function onUserDeleted(UserDeletedEvent $event): void
    {
        $this->bus->dispatch(new UserDeletedFlashMessage($event->getUser()->getId()));
    }
}
