<?php

declare(strict_types=1);

namespace App\EventSubscriber\UserRole;

use App\Event\UserRole\UserRoleUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRoleEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserRoleUpdatedEvent::class => 'onUserRoleUpdated',
        ];
    }

    public function onUserRoleUpdated(UserRoleUpdatedEvent $event): void
    {
    }
}
