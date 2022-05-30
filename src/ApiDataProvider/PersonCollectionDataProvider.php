<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\PersonDto;
use App\Factory\PersonFactory;
use App\PageView\PersonPageView;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class PersonCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
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

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool
    {
        return PersonDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable
    {
        try {
            $criteria = new PersonPageView(
                (int) $this->request->getCurrentRequest()->get('page', 1)
            );

            if ($this->request->getCurrentRequest()->get('delegates')) {
                $criteria->isDelegate = true;
            }
            if ($this->request->getCurrentRequest()->get('referees')) {
                $criteria->isReferee = true;
            }
            if ($this->request->getCurrentRequest()->get('referee-observers')) {
                $criteria->isRefereeObserver = true;
            }

            $people = $this->repository->findByCriteria($criteria);
        } catch (\Exception $e) {
            return [];
        }

        $dtos = \array_map(
            fn($person) => $this->factory->createDto($person),
            (array) $people->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos, 0, $this->mgApiItemsPerPage, $people->getNbResults()
        );
    }
}
