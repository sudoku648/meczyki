<?php

declare(strict_types=1);

namespace App\Controller\Api\People;

use App\ApiDataProvider\DtoPaginator;
use App\Controller\AbstractController;
use App\Factory\PersonFactory;
use App\PageView\PersonPageView;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Delegates extends AbstractController
{
    private int $mgApiItemsPerPage;
    private PersonRepository $repository;
    private PersonFactory $factory;
    private RequestStack $request;

    public function __construct(
        int $mgApiItemsPerPage,
        PersonRepository $repository,
        PersonFactory $factory,
        RequestStack $request
    )
    {
        $this->mgApiItemsPerPage = $mgApiItemsPerPage;
        $this->repository        = $repository;
        $this->factory           = $factory;
        $this->request           = $request;
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
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($delegate) => $this->factory->createDto($delegate),
            (array) $delegates->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $delegates->getNbResults()
        );
    }
}
