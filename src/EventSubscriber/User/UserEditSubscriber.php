<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserUpdatedEvent::class => 'onUserUpdated',
        ];
    }

    public function onUserUpdated(UserUpdatedEvent $event): void
    {
    }
}
