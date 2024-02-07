<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameDeletedEvent::class => 'onMatchGameDeleted',
        ];
    }

    public function onMatchGameDeleted(MatchGameDeletedEvent $event): void
    {
    }
}
