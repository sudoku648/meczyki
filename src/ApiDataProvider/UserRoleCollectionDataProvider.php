<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\UserRoleDto;
use App\Factory\UserRoleFactory;
use App\PageView\UserRolePageView;
use App\Repository\UserRoleRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class UserRoleCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private int $mgApiItemsPerPage;
    private UserRoleRepository $repository;
    private UserRoleFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        UserRoleRepository $repository,
        UserRoleFactory $factory,
        RequestStack $request
    )
    {
        $this->mgApiItemsPerPage = $mgApiItemsPerPage;
        $this->repository        = $repository;
        $this->factory           = $factory;
        $this->request           = $request;
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool
    {
        return UserRoleDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new UserRolePageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $userRoles = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($userRole) => $this->factory->createDto($userRole),
            (array) $userRoles->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $userRoles->getNbResults()
        );
    }
}
