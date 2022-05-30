<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\ApiDataProvider\DtoPaginator;
use App\Controller\AbstractController;
use App\Entity\Club;
use App\Factory\TeamFactory;
use App\PageView\TeamPageView;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ClubTeams extends AbstractController
{
    private int $mgApiItemsPerPage;
    private TeamRepository $repository;
    private TeamFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        TeamRepository $repository,
        TeamFactory $factory,
        RequestStack $request
    )
    {
        $this->mgApiItemsPerPage = $mgApiItemsPerPage;
        $this->repository        = $repository;
        $this->factory           = $factory;
        $this->request           = $request;
    }

    public function __invoke(Club $club)
    {
        try {
            $criteria = new TeamPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );
            $criteria->club = $club;

            $teams = $this->repository->findByCriteria($criteria);

            $this->repository->hydrate(...$teams);
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
