<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGameBill;

use App\Event\MatchGameBill\MatchGameBillUpdatedEvent;
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
