<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\GameTypeDto;
use App\Factory\GameTypeFactory;
use App\Repository\GameTypeRepository;

final class GameTypeItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private GameTypeRepository $repository;
    private GameTypeFactory $factory;

    public function __construct(GameTypeRepository $repository, GameTypeFactory $factory)
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
        return GameTypeDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?GameTypeDto
    {
        $gameType = $this->repository->find($id);

        return $gameType ? $this->factory->createDto($gameType) : null;
    }
}
