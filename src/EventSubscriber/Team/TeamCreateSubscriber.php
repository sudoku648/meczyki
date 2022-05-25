<?php

declare(strict_types=1);

namespace App\EventSubscriber\Team;

use App\Event\Team\TeamCreatedEvent;
use App\Message\Flash\Team\TeamCreatedFlashMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TeamCreateSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TeamCreatedEvent::class => 'onTeamCreated',
        ];
    }

    public function onTeamCreated(TeamCreatedEvent $event): void
    {
        $this->bus->dispatch(new TeamCreatedFlashMessage($event->getTeam()->getId()));
    }
}
