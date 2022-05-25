<?php

declare(strict_types=1);

namespace App\EventSubscriber\Team;

use App\Event\Team\TeamUpdatedEvent;
use App\Message\Flash\Team\TeamUpdatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TeamEditSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TeamUpdatedEvent::class => 'onTeamUpdated',
        ];
    }

    public function onTeamUpdated(TeamUpdatedEvent $event): void
    {
        $this->bus->dispatch(new TeamUpdatedFlashMessage($event->getTeam()->getId()));
    }
}
