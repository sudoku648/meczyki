<?php

declare(strict_types=1);

namespace App\EventSubscriber\GameType;

use App\Event\GameType\GameTypeCreatedEvent;
use App\Message\Flash\GameType\GameTypeCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameTypeCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GameTypeCreatedEvent::class => 'onGameTypeCreated',
        ];
    }

    public function onGameTypeCreated(GameTypeCreatedEvent $event): void
    {
        $this->bus->dispatch(new GameTypeCreatedFlashMessage($event->getGameType()->getId()));
    }
}
