<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameCreatedEvent::class => 'onMatchGameCreated',
        ];
    }

    public function onMatchGameCreated(MatchGameCreatedEvent $event): void
    {
    }
}
