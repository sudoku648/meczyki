<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserHasBeenSeenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserShowSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserHasBeenSeenEvent::class => 'onUserSeen',
        ];
    }

    public function onUserSeen(UserHasBeenSeenEvent $event): void
    {
    }
}
