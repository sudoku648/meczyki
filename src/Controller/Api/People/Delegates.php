<?php

declare(strict_types=1);

namespace App\Controller\Api\People;

use App\ApiDataProvider\DtoPaginator;
use App\Controller\AbstractController;
use App\Factory\PersonFactory;
use App\PageView\PersonPageView;
use App\Repository\PersonRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

class Delegates extends AbstractController
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly PersonRepository $repository,
        private readonly PersonFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function __invoke()
    {
        try {
            $criteria = new PersonPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );
            $criteria->isDelegate = true;

            $delegates = $this->repository->findByCriteria($criteria);

            $this->repository->hydrate(...$delegates);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($delegate) => $this->factory->createDto($delegate),
            (array) $delegates->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $delegates->getNbResults()
        );
    }
}
