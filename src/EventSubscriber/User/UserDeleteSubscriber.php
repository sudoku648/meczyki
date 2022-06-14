<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserDeletedEvent::class => 'onUserDeleted',
        ];
    }

    public function onUserDeleted(UserDeletedEvent $event): void
    {
    }
}
