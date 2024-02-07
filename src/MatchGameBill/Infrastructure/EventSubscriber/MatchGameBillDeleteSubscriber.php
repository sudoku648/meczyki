<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\EventSubscriber;

use Sudoku648\Meczyki\MatchGameBill\Domain\Event\MatchGameBillDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MatchGameBillDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameBillDeletedEvent::class => 'onMatchGameBillDeleted',
        ];
    }

    public function onMatchGameBillDeleted(MatchGameBillDeletedEvent $event): void
    {
    }
}
