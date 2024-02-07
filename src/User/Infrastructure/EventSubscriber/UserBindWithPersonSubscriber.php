<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\User\Domain\Event\UserBindWithPersonEvent;
use Sudoku648\Meczyki\User\Domain\Event\UserUnbindWithPersonEvent;
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
