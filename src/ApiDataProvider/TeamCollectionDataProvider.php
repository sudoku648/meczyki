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
use Symfony\Component\HttpFoundation\RequestStack;

final class TeamCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private int $mgApiItemsPerPage;
    private ClubRepository $clubRepository;
    private TeamRepository $repository;
    private TeamFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        ClubRepository $clubRepository,
        TeamRepository $repository,
        TeamFactory $factory,
        RequestStack $request
    )
    {
        $this->mgApiItemsPerPage = $mgApiItemsPerPage;
        $this->clubRepository    = $clubRepository;
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
        return TeamDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new TeamPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            if ($id = $this->request->getCurrentRequest()->get('club')) {
                $criteria->club = $this->clubRepository->find($id);
            }
            $teams = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($team) => $this->factory->createDto($team),
            (array) $teams->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $teams->getNbResults()
        );
    }
}
