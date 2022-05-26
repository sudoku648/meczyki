<?php

declare(strict_types=1);

namespace App\EventSubscriber\GameType;

use App\Event\GameType\GameTypeUpdatedEvent;
use App\Message\Flash\GameType\GameTypeUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameTypeEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GameTypeUpdatedEvent::class => 'onGameTypeUpdated',
        ];
    }

    public function onGameTypeUpdated(GameTypeUpdatedEvent $event): void
    {
        $this->bus->dispatch(new GameTypeUpdatedFlashMessage($event->getGameType()->getId()));
    }
}
