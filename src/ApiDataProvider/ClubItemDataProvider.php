<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\ClubDto;
use App\Factory\ClubFactory;
use App\Repository\ClubRepository;

final class ClubItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private ClubRepository $repository;
    private ClubFactory $factory;

    public function __construct(ClubRepository $repository, ClubFactory $factory)
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
        return ClubDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?ClubDto
    {
        $club = $this->repository->find($id);

        return $club ? $this->factory->createDto($club) : null;
    }
}
