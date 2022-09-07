<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\ClubDto;
use App\Factory\ClubFactory;
use App\PageView\ClubPageView;
use App\Repository\ClubRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class ClubCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly ClubRepository $repository,
        private readonly ClubFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return ClubDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        try {
            $criteria = new ClubPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );
            if ($nameLike = $this->request->getCurrentRequest()->get('name')) {
                $criteria->nameLike = $nameLike;
            }

            $clubs = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($club) => $this->factory->createDto($club),
            (array) $clubs->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $clubs->getNbResults()
        );
    }
}
