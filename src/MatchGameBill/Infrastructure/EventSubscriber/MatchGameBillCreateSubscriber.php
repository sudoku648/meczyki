<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\MatchGameBill\Domain\Event\MatchGameBillCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameBillCreateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameBillCreatedEvent::class => 'onMatchGameBillCreated',
        ];
    }

    public function onMatchGameBillCreated(MatchGameBillCreatedEvent $event): void
    {
    }
}
