<?php

declare(strict_types=1);

namespace App\EventSubscriber\UserRole;

use App\Event\UserRole\UserRoleCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRoleCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserRoleCreatedEvent::class => 'onUserRoleCreated',
        ];
    }

    public function onUserRoleCreated(UserRoleCreatedEvent $event): void
    {
    }
}
