<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\PersonDto;
use App\Factory\PersonFactory;
use App\PageView\PersonPageView;
use App\Repository\PersonRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_map;

final class PersonCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly int $mgApiItemsPerPage,
        private readonly PersonRepository $repository,
        private readonly PersonFactory $factory,
        private readonly RequestStack $request
    ) {
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return PersonDto::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
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
            if ($fullNameLike = $this->request->getCurrentRequest()->get('fullname')) {
                $criteria->fullNameLike = $fullNameLike;
            }

            $people = $this->repository->findByCriteria($criteria);
        } catch (Exception $e) {
            return [];
        }

        $dtos = array_map(
            fn ($person) => $this->factory->createDto($person),
            (array) $people->getCurrentPageResults()
        );

        return new DtoPaginator(
            $dtos,
            0,
            $this->mgApiItemsPerPage,
            $people->getNbResults()
        );
    }
}
