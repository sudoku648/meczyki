<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGame;

use App\Event\MatchGame\MatchGameCreatedEvent;
use App\Message\Flash\MatchGame\MatchGameCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MatchGameCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameCreatedEvent::class => 'onMatchGameCreated',
        ];
    }

    public function onMatchGameCreated(MatchGameCreatedEvent $event): void
    {
        $this->bus->dispatch(new MatchGameCreatedFlashMessage($event->getMatchGame()->getId()));
    }
}
