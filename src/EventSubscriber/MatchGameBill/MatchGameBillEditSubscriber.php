<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGameBill;

use App\Event\MatchGameBill\MatchGameBillUpdatedEvent;
use App\Message\Flash\MatchGameBill\MatchGameBillUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MatchGameBillEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameBillUpdatedEvent::class => 'onMatchGameBillUpdated',
        ];
    }

    public function onMatchGameBillUpdated(MatchGameBillUpdatedEvent $event): void
    {
        $this->bus->dispatch(new MatchGameBillUpdatedFlashMessage($event->getMatchGameBill()->getId()));
    }
}
