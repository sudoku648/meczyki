<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserCreatedEvent;
use App\Message\Flash\User\UserCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'onUserCreated',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event): void
    {
        $this->bus->dispatch(new UserCreatedFlashMessage($event->getUser()->getId()));
    }
}
