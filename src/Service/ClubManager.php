<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClubDto;
use App\Entity\Club;
use App\Event\Club\ClubCreatedEvent;
use App\Event\Club\ClubDeletedEvent;
use App\Event\Club\ClubUpdatedEvent;
use App\Factory\ClubFactory;
use App\Message\DeleteImageMessage;
use App\Service\Contracts\ContentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ClubManager implements ContentManagerInterface
{
    private EventDispatcherInterface $dispatcher;
    private EntityManagerInterface $entityManager;
    private ClubFactory $factory;
    private MessageBusInterface $bus;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        ClubFactory $factory,
        MessageBusInterface $bus
    )
    {
        $this->dispatcher    = $dispatcher;
        $this->entityManager = $entityManager;
        $this->factory       = $factory;
        $this->bus           = $bus;
    }

    public function create(ClubDto $dto): Club
    {
        $club = $this->factory->createFromDto($dto);

        $club->setEmblem($dto->emblem);

        $this->entityManager->persist($club);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new ClubCreatedEvent($club));

        return $club;
    }

    public function edit(Club $club, ClubDto $dto): Club
    {
        $club->setName($dto->name);
        $oldEmblem = $club->getEmblem();
        $club->setEmblem($dto->emblem);

        $this->entityManager->flush();

        if ($oldEmblem && $dto->emblem !== $oldEmblem) {
            $this->bus->dispatch(new DeleteImageMessage($oldEmblem->getFilePath()));
        }

        $this->dispatcher->dispatch(new ClubUpdatedEvent($club));

        return $club;
    }

    public function delete(Club $club): void
    {
        $emblem = $club->getEmblem();

        $club->setEmblem(null);

        if ($emblem) {
            $this->bus->dispatch(new DeleteImageMessage($emblem->getFilePath()));
        }

        $this->dispatcher->dispatch(new ClubDeletedEvent($club));

        $this->entityManager->remove($club);
        $this->entityManager->flush();
    }

    public function detachEmblem(Club $club): void
    {
        $emblem = $club->getEmblem();

        $club->setEmblem(null);

        $this->entityManager->persist($club);
        $this->entityManager->flush();

        $this->bus->dispatch(new DeleteImageMessage($emblem->getFilePath()));
    }

    public function createDto(Club $club): ClubDto
    {
        return $this->factory->createDto($club);
    }
}