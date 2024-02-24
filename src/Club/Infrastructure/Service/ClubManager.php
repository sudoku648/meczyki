<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\Service;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Club\Domain\Service\ClubManagerInterface;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Club\Frontend\Dto\ClubDto;
use Sudoku648\Meczyki\Club\Frontend\Factory\ClubFactory;
use Sudoku648\Meczyki\Image\Domain\Message\DeleteImageMessage;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ClubManager implements ClubManagerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ClubFactory $factory,
        private ClubRepositoryInterface $repository,
    ) {
    }

    public function create(ClubDto $dto): Club
    {
        $club = $this->factory->createFromDto($dto);

        $club->setEmblem($dto->emblem);

        $this->repository->persist($club);

        return $club;
    }

    public function edit(Club $club, ClubDto $dto): Club
    {
        $club->setName(ClubName::fromString($dto->name));
        $oldEmblem = $club->getEmblem();
        $club->setEmblem($dto->emblem);
        $club->setUpdatedAt();

        $this->repository->persist($club);

        if ($oldEmblem && $dto->emblem !== $oldEmblem) {
            $this->messageBus->dispatch(new DeleteImageMessage($oldEmblem->getFilePath()));
        }

        return $club;
    }

    public function delete(Club $club): void
    {
        $emblem = $club->getEmblem();

        $club->setEmblem(null);

        if ($emblem) {
            $this->messageBus->dispatch(new DeleteImageMessage($emblem->getFilePath()));
        }

        $this->repository->remove($club);
    }

    public function detachEmblem(Club $club): void
    {
        $emblem = $club->getEmblem();

        $club->setEmblem(null);

        $this->repository->persist($club);

        $this->messageBus->dispatch(new DeleteImageMessage($emblem->getFilePath()));
    }

    public function createDto(Club $club): ClubDto
    {
        return $this->factory->createDto($club);
    }
}
