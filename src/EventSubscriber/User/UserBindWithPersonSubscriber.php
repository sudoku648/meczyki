<?php

declare(strict_types=1);

namespace App\EventSubscriber\User;

use App\Event\User\UserBindWithPersonEvent;
use App\Event\User\UserUnbindWithPersonEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserBindWithPersonSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserBindWithPersonEvent::class   => 'onUserBoundWithPerson',
            UserUnbindWithPersonEvent::class => 'onUserUnboundWithPerson',
        ];
    }

    public function onUserBoundWithPerson(UserBindWithPersonEvent $event): void
    {
    }

    public function onUserUnboundWithPerson(UserUnbindWithPersonEvent $event): void
    {
    }
}
