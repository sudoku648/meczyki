<?php

declare(strict_types=1);

namespace App\EventSubscriber\GameType;

use App\Event\GameType\GameTypeDeletedEvent;
use App\Message\Flash\GameType\GameTypeDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GameTypeDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GameTypeDeletedEvent::class => 'onGameTypeDeleted',
        ];
    }

    public function onGameTypeDeleted(GameTypeDeletedEvent $event): void
    {
        $this->bus->dispatch(new GameTypeDeletedFlashMessage($event->getGameType()->getId()));
    }
}
