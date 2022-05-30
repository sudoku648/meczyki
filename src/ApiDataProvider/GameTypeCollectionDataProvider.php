<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\GameTypeDto;
use App\Factory\GameTypeFactory;
use App\PageView\GameTypePageView;
use App\Repository\GameTypeRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class GameTypeCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private int $mgApiItemsPerPage;
    private GameTypeRepository $repository;
    private GameTypeFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        GameTypeRepository $repository,
        GameTypeFactory $factory,
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
        return GameTypeDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new GameTypePageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $gameTypes = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($gameType) => $this->factory->createDto($gameType),
            (array) $gameTypes->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $gameTypes->getNbResults()
        );
    }
}
