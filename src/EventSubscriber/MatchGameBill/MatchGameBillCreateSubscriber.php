<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGameBill;

use App\Event\MatchGameBill\MatchGameBillCreatedEvent;
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
