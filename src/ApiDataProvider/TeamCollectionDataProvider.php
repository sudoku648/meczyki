<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\TeamDto;
use App\Factory\TeamFactory;
use App\PageView\TeamPageView;
use App\Repository\ClubRepository;
use App\Repository\TeamRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class TeamCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly ClubRepository $clubRepository,
        private readonly TeamRepository $repository,
        private readonly TeamFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return TeamDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        try {
            $criteria = new TeamPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );
            if ($id = $this->request->getCurrentRequest()->get('club')) {
                $criteria->club = $this->clubRepository->find($id);
            }
            if ($nameLike = $this->request->getCurrentRequest()->get('name')) {
                $criteria->nameLike = $nameLike;
            }
            if ($clubNameLike = $this->request->getCurrentRequest()->get('clubname')) {
                $criteria->clubNameLike = $clubNameLike;
            }

            $teams = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($team) => $this->factory->createDto($team),
            (array) $teams->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $teams->getNbResults()
        );
    }
}
