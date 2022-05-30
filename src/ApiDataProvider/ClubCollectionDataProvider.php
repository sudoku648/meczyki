<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\ClubDto;
use App\Factory\ClubFactory;
use App\PageView\ClubPageView;
use App\Repository\ClubRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class ClubCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private int $mgApiItemsPerPage;
    private ClubRepository $repository;
    private ClubFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        ClubRepository $repository,
        ClubFactory $factory,
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
        return ClubDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new ClubPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $clubs = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($club) => $this->factory->createDto($club),
            (array) $clubs->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $clubs->getNbResults()
        );
    }
}
