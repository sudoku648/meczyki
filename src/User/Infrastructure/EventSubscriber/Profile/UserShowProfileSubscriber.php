<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\EventSubscriber\Profile;

use Sudoku648\Meczyki\User\Domain\Event\Profile\UserProfileHasBeenSeenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserShowProfileSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            UserProfileHasBeenSeenEvent::class => 'onUserProfileSeen',
        ];
    }

    public function onUserProfileSeen(UserProfileHasBeenSeenEvent $event): void
    {
    }
}
