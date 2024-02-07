<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\User\Domain\Event\UserDeletedEvent;
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
