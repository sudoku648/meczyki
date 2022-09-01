<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\MatchGameDto;
use App\Factory\MatchGameFactory;
use App\PageView\MatchGamePageView;
use App\Repository\MatchGameRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class MatchGameCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly MatchGameRepository $repository,
        private readonly MatchGameFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return MatchGameDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        try {
            $criteria = new MatchGamePageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $matchGames = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($matchGame) => $this->factory->createDto($matchGame),
            (array) $matchGames->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $matchGames->getNbResults()
        );
    }
}
