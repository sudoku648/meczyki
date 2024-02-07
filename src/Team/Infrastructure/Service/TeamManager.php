<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Event\TeamCreatedEvent;
use Sudoku648\Meczyki\Team\Domain\Event\TeamDeletedEvent;
use Sudoku648\Meczyki\Team\Domain\Event\TeamUpdatedEvent;
use Sudoku648\Meczyki\Team\Domain\Service\TeamManagerInterface;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamName;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamShortName;
use Sudoku648\Meczyki\Team\Frontend\Dto\TeamDto;
use Sudoku648\Meczyki\Team\Frontend\Factory\TeamFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

readonly class TeamManager implements TeamManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private TeamFactory $factory,
    ) {
    }

    public function create(TeamDto $dto): Team
    {
        $team = $this->factory->createFromDto($dto);
        $team->getClub()->setUpdatedAt();

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new TeamCreatedEvent($team));

        return $team;
    }

    public function edit(Team $team, TeamDto $dto): Team
    {
        Assert::same($team->getClub()->getId(), $dto->club->getId());

        $team->setName(TeamName::fromString($dto->name));
        $team->setShortName(TeamShortName::fromString($dto->shortName));
        $team->setClub($dto->club);
        $team->setUpdatedAt();
        $team->getClub()->setUpdatedAt();

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new TeamUpdatedEvent($team));

        return $team;
    }

    public function delete(Team $team): void
    {
        $this->dispatcher->dispatch(new TeamDeletedEvent($team));

        $this->entityManager->remove($team);
        $this->entityManager->flush();
    }

    public function createDto(Team $team): TeamDto
    {
        return $this->factory->createDto($team);
    }
}
