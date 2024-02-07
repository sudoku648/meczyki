<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\User\Domain\Event\UserCreatedEvent;
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
