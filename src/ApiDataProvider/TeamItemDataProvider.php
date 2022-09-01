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
    public function __construct(
        private readonly TeamRepository $repository,
        private readonly TeamFactory $factory
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return TeamDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?TeamDto {
        $team = $this->repository->find($id);

        return $team ? $this->factory->createDto($team) : null;
    }
}
