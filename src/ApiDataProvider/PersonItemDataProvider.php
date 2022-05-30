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
    private PersonRepository $repository;
    private PersonFactory $factory;

    public function __construct(PersonRepository $repository, PersonFactory $factory)
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
        return PersonDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?PersonDto
    {
        $person = $this->repository->find($id);

        return $person ? $this->factory->createDto($person) : null;
    }
}
