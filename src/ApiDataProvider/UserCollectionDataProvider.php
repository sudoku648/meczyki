<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\UserDto;
use App\Factory\UserFactory;
use App\PageView\UserPageView;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class UserCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly UserRepository $repository,
        private readonly UserFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return UserDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        try {
            $criteria = new UserPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $users = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($user) => $this->factory->createDto($user),
            (array) $users->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $users->getNbResults()
        );
    }
}
