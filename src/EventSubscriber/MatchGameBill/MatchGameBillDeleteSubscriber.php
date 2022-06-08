<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGameBill;

use App\Event\MatchGameBill\MatchGameBillDeletedEvent;
use App\Message\Flash\MatchGameBill\MatchGameBillDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MatchGameBillDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameBillDeletedEvent::class => 'onMatchGameBillDeleted',
        ];
    }

    public function onMatchGameBillDeleted(MatchGameBillDeletedEvent $event): void
    {
        $this->bus->dispatch(new MatchGameBillDeletedFlashMessage($event->getMatchGameBill()->getId()));
    }
}
