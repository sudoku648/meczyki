<?php

declare(strict_types=1);

namespace App\EventSubscriber\MatchGame;

use App\Event\MatchGame\MatchGameUpdatedEvent;
use App\Message\Flash\MatchGame\MatchGameUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MatchGameEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MatchGameUpdatedEvent::class => 'onMatchGameUpdated',
        ];
    }

    public function onMatchGameUpdated(MatchGameUpdatedEvent $event): void
    {
        $this->bus->dispatch(new MatchGameUpdatedFlashMessage($event->getMatchGame()->getId()));
    }
}
