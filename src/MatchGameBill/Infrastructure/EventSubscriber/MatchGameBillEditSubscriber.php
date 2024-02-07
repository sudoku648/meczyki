<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\MatchGameBill\Domain\Event\MatchGameBillUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameBillEditSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameBillUpdatedEvent::class => 'onMatchGameBillUpdated',
        ];
    }

    public function onMatchGameBillUpdated(MatchGameBillUpdatedEvent $event): void
    {
    }
}
