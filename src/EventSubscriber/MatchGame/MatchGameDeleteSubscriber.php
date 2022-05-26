<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGame;

use App\Event\MatchGame\MatchGameDeletedEvent;
use App\Message\Flash\MatchGame\MatchGameDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MatchGameDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameDeletedEvent::class => 'onMatchGameDeleted',
        ];
    }

    public function onMatchGameDeleted(MatchGameDeletedEvent $event): void
    {
        $this->bus->dispatch(new MatchGameDeletedFlashMessage($event->getMatchGame()->getId()));
    }
}
