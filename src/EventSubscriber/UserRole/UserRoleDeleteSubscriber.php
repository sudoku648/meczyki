<?php

declare(strict_types=1);

namespace App\EventSubscriber\UserRole;

use App\Event\UserRole\UserRoleDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRoleDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserRoleDeletedEvent::class => 'onUserRoleDeleted',
        ];
    }

    public function onUserRoleDeleted(UserRoleDeletedEvent $event): void
    {
    }
}
