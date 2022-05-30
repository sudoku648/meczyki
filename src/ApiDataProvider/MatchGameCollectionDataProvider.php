<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\MatchGameDto;
use App\Factory\MatchGameFactory;
use App\PageView\MatchGamePageView;
use App\Repository\MatchGameRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class MatchGameCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private int $mgApiItemsPerPage;
    private MatchGameRepository $repository;
    private MatchGameFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        MatchGameRepository $repository,
        MatchGameFactory $factory,
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
        return MatchGameDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new MatchGamePageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $matchGames = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($matchGame) => $this->factory->createDto($matchGame),
            (array) $matchGames->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $matchGames->getNbResults()
        );
    }
}
