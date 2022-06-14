<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'onUserCreated',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event): void
    {
    }
}
