<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameUpdatedEvent::class => 'onMatchGameUpdated',
        ];
    }

    public function onMatchGameUpdated(MatchGameUpdatedEvent $event): void
    {
    }
}
