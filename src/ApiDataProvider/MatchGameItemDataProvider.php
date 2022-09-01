<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\MatchGameDto;
use App\Factory\MatchGameFactory;
use App\Repository\MatchGameRepository;

final class MatchGameItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly MatchGameRepository $repository,
        private readonly MatchGameFactory $factory
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return MatchGameDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?MatchGameDto {
        $matchGame = $this->repository->find($id);

        return $matchGame ? $this->factory->createDto($matchGame) : null;
    }
}
