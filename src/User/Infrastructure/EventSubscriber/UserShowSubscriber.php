<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\User\Domain\Event\UserHasBeenSeenEvent;
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
