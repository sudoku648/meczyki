<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\ApiDataProvider\DtoPaginator;
use App\Controller\AbstractController;
use App\Entity\Club;
use App\Factory\TeamFactory;
use App\PageView\TeamPageView;
use App\Repository\TeamRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

class ClubTeams extends AbstractController
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly TeamRepository $repository,
        private readonly TeamFactory $factory,
        private readonly RequestStack $request
    ) {
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
