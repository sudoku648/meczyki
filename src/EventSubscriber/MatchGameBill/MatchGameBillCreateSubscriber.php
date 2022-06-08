<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGameBill;

use App\Event\MatchGameBill\MatchGameBillCreatedEvent;
use App\Message\Flash\MatchGameBill\MatchGameBillCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MatchGameBillCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameBillCreatedEvent::class => 'onMatchGameBillCreated',
        ];
    }

    public function onMatchGameBillCreated(MatchGameBillCreatedEvent $event): void
    {
        $this->bus->dispatch(new MatchGameBillCreatedFlashMessage($event->getMatchGameBill()->getId()));
    }
}
