<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\TeamDto;
use App\Factory\TeamFactory;
use App\Repository\TeamRepository;

final class TeamItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private TeamRepository $repository;
    private TeamFactory $factory;

    public function __construct(TeamRepository $repository, TeamFactory $factory)
    {
        $this->repository = $repository;
        $this->factory    = $factory;
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool
    {
        return TeamDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?TeamDto
    {
        $team = $this->repository->find($id);

        return $team ? $this->factory->createDto($team) : null;
    }
}
