<?php

declare(strict_types=1);

namespace App\EventSubscriber\Team;

use App\Event\Team\TeamDeletedEvent;
use App\Message\Flash\Team\TeamDeletedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TeamDeleteSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TeamDeletedEvent::class => 'onTeamDeleted',
        ];
    }

    public function onTeamDeleted(TeamDeletedEvent $event): void
    {
        $this->bus->dispatch(new TeamDeletedFlashMessage($event->getTeam()->getId()));
    }
}
