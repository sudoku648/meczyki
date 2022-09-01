<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\GameTypeDto;
use App\Factory\GameTypeFactory;
use App\PageView\GameTypePageView;
use App\Repository\GameTypeRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class GameTypeCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly GameTypeRepository $repository,
        private readonly GameTypeFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return GameTypeDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        try {
            $criteria = new GameTypePageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            $gameTypes = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($gameType) => $this->factory->createDto($gameType),
            (array) $gameTypes->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $gameTypes->getNbResults()
        );
    }
}
