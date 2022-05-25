<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClubDto;
use App\Entity\Club;
use App\Event\Club\ClubCreatedEvent;
use App\Event\Club\ClubDeletedEvent;
use App\Event\Club\ClubUpdatedEvent;
use App\Factory\ClubFactory;
use App\Service\Contracts\ContentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClubManager implements ContentManagerInterface
{
    private EventDispatcherInterface $dispatcher;
    private EntityManagerInterface $entityManager;
    private ClubFactory $factory;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        ClubFactory $factory
    )
    {
        $this->dispatcher    = $dispatcher;
        $this->entityManager = $entityManager;
        $this->factory       = $factory;
    }

    public function create(ClubDto $dto): Club
    {
        $club = $this->factory->createFromDto($dto);

        $this->entityManager->persist($club);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new ClubCreatedEvent($club));

        return $club;
    }

    public function edit(Club $club, ClubDto $dto): Club
    {
        $club->setName($dto->name);

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new ClubUpdatedEvent($club));

        return $club;
    }

    public function delete(Club $club): void
    {
        $this->dispatcher->dispatch(new ClubDeletedEvent($club));

        $this->entityManager->remove($club);
        $this->entityManager->flush();
    }

    public function createDto(Club $club): ClubDto
    {
        return $this->factory->createDto($club);
    }
}
