<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Event\ClubCreatedEvent;
use Sudoku648\Meczyki\Club\Domain\Event\ClubDeletedEvent;
use Sudoku648\Meczyki\Club\Domain\Event\ClubUpdatedEvent;
use Sudoku648\Meczyki\Club\Domain\Service\ClubManagerInterface;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Club\Frontend\Dto\ClubDto;
use Sudoku648\Meczyki\Club\Frontend\Factory\ClubFactory;
use Sudoku648\Meczyki\Image\Domain\Message\DeleteImageMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ClubManager implements ClubManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private ClubFactory $factory,
    ) {
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
        $club->setName(ClubName::fromString($dto->name));
        $oldEmblem = $club->getEmblem();
        $club->setEmblem($dto->emblem);
        $club->setUpdatedAt();

        $this->entityManager->flush();

        if ($oldEmblem && $dto->emblem !== $oldEmblem) {
            $this->messageBus->dispatch(new DeleteImageMessage($oldEmblem->getFilePath()));
        }

        $this->dispatcher->dispatch(new ClubUpdatedEvent($club));

        return $club;
    }

    public function delete(Club $club): void
    {
        $emblem = $club->getEmblem();

        $club->setEmblem(null);

        if ($emblem) {
            $this->messageBus->dispatch(new DeleteImageMessage($emblem->getFilePath()));
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

        $this->messageBus->dispatch(new DeleteImageMessage($emblem->getFilePath()));
    }

    public function createDto(Club $club): ClubDto
    {
        return $this->factory->createDto($club);
    }
}
