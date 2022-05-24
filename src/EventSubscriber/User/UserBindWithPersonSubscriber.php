<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserBindWithPersonEvent;
use App\Event\User\UserNotBindWithPersonEvent;
use App\Event\User\UserNotUnbindWithPersonEvent;
use App\Event\User\UserUnbindWithPersonEvent;
use App\Message\Flash\User\UserBoundWithPersonFlashMessage;
use App\Message\Flash\User\UserNotBoundWithPersonFlashMessage;
use App\Message\Flash\User\UserNotUnboundWithPersonFlashMessage;
use App\Message\Flash\User\UserUnboundWithPersonFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserBindWithPersonSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserBindWithPersonEvent::class => 'onUserBoundWithPerson',
            UserNotBindWithPersonEvent::class => 'onUserNotBoundWithPerson',
            UserUnbindWithPersonEvent::class => 'onUserUnboundWithPerson',
            UserNotUnbindWithPersonEvent::class => 'onUserNotUnboundWithPerson',
        ];
    }

    public function onUserBoundWithPerson(UserBindWithPersonEvent $event): void
    {
        $this->bus->dispatch(new UserBoundWithPersonFlashMessage($event->getUser()->getId()));
    }

    public function onUserNotBoundWithPerson(UserNotBindWithPersonEvent $event): void
    {
        $this->bus->dispatch(new UserNotBoundWithPersonFlashMessage($event->getUser()->getId()));
    }

    public function onUserUnboundWithPerson(UserUnbindWithPersonEvent $event): void
    {
        $this->bus->dispatch(new UserUnboundWithPersonFlashMessage($event->getUser()->getId()));
    }

    public function onUserNotUnboundWithPerson(UserNotUnbindWithPersonEvent $event): void
    {
        $this->bus->dispatch(new UserNotUnboundWithPersonFlashMessage($event->getUser()->getId()));
    }
}
