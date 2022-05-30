<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\UserDto;
use App\Factory\UserFactory;
use App\PageView\UserPageView;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class UserCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private int $mgApiItemsPerPage;
    private UserRepository $repository;
    private UserFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        UserRepository $repository,
        UserFactory $factory,
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
        return UserDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new UserPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $users = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($user) => $this->factory->createDto($user),
            (array) $users->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $users->getNbResults()
        );
    }
}
