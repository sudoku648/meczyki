<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserUpdatedEvent;
use App\Message\Flash\User\UserUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserUpdatedEvent::class => 'onUserUpdated',
        ];
    }

    public function onUserUpdated(UserUpdatedEvent $event): void
    {
        $this->bus->dispatch(new UserUpdatedFlashMessage($event->getUser()->getId()));
    }
}
