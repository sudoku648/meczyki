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
    public function __construct(
        private readonly GameTypeRepository $repository,
        private readonly GameTypeFactory $factory
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return GameTypeDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?GameTypeDto {
        $gameType = $this->repository->find($id);

        return $gameType ? $this->factory->createDto($gameType) : null;
    }
}
