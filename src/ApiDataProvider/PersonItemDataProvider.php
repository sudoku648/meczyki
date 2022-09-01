<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\PersonDto;
use App\Factory\PersonFactory;
use App\Repository\PersonRepository;

final class PersonItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly PersonRepository $repository,
        private readonly PersonFactory $factory
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return PersonDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?PersonDto {
        $person = $this->repository->find($id);

        return $person ? $this->factory->createDto($person) : null;
    }
}
