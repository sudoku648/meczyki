<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\User\Domain\Event\UserUpdatedEvent;
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
