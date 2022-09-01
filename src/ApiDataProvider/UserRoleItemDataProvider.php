<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\UserRoleDto;
use App\Factory\UserRoleFactory;
use App\Repository\UserRoleRepository;

final class UserRoleItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly UserRoleRepository $repository,
        private readonly UserRoleFactory $factory
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return UserRoleDto::class === $resourceClass;
    }

    public function getItem(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ): ?UserRoleDto {
        $userRole = $this->repository->find($id);

        return $userRole ? $this->factory->createDto($userRole) : null;
    }
}
