<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\User\Domain\Event\UserActivateEvent;
use Sudoku648\Meczyki\User\Domain\Event\UserDeactivateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserActivateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserActivateEvent::class   => 'onUserActivated',
            UserDeactivateEvent::class => 'onUserDeactivated',
        ];
    }

    public function onUserActivated(UserActivateEvent $event): void
    {
    }

    public function onUserDeactivated(UserDeactivateEvent $event): void
    {
    }
}
