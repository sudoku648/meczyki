<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\TeamDto;
use App\Entity\Team;
use App\Event\Team\TeamCreatedEvent;
use App\Event\Team\TeamDeletedEvent;
use App\Event\Team\TeamUpdatedEvent;
use App\Factory\TeamFactory;
use App\Service\Contracts\ContentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

class TeamManager implements ContentManagerInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly EntityManagerInterface $entityManager,
        private readonly TeamFactory $factory
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

        $team->setFullName($dto->fullName);
        $team->setShortName($dto->shortName);
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
