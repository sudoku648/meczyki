<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\Service;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Persistence\TeamRepositoryInterface;
use Sudoku648\Meczyki\Team\Domain\Service\TeamManagerInterface;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamName;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamShortName;
use Sudoku648\Meczyki\Team\Frontend\Dto\TeamDto;
use Sudoku648\Meczyki\Team\Frontend\Factory\TeamFactory;
use Webmozart\Assert\Assert;

readonly class TeamManager implements TeamManagerInterface
{
    public function __construct(
        private TeamFactory $factory,
        private TeamRepositoryInterface $repository,
    ) {
    }

    public function create(TeamDto $dto): Team
    {
        $team = $this->factory->createFromDto($dto);
        $team->getClub()->setUpdatedAt();

        $this->repository->persist($team);

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

        $this->repository->persist($team);

        return $team;
    }

    public function delete(Team $team): void
    {
        $this->repository->remove($team);
    }

    public function createDto(Team $team): TeamDto
    {
        return $this->factory->createDto($team);
    }
}
