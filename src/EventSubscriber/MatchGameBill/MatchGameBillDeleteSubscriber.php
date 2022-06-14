<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGameBill;

use App\Event\MatchGameBill\MatchGameBillDeletedEvent;
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
