<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\UserRoleDto;
use App\Factory\UserRoleFactory;
use App\PageView\UserRolePageView;
use App\Repository\UserRoleRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class UserRoleCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly UserRoleRepository $repository,
        private readonly UserRoleFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return UserRoleDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        try {
            $criteria = new UserRolePageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $userRoles = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($userRole) => $this->factory->createDto($userRole),
            (array) $userRoles->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $userRoles->getNbResults()
        );
    }
}
