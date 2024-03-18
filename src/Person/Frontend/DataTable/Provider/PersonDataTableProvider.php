<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Provider;

use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Person\Frontend\DataTable\Factory\DataTablePersonCriteriaFactory;
use Sudoku648\Meczyki\Person\Frontend\DataTable\Factory\DataTablePersonResponseFactory;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class PersonDataTableProvider
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private PersonRepositoryInterface $repository,
        private DataTablePersonResponseFactory $responseFactory,
    ) {
    }

    public function provide(MatchGameFunction $function = null): DataTable
    {
        if (!$this->security->isGranted(PersonVoter::LIST)) {
            return new DataTable();
        }

        $params = DataTableParamsFactory::fromRequest($this->requestStack->getCurrentRequest());

        $criteria = DataTablePersonCriteriaFactory::fromParams($params);

        $criteria->isDelegate        = $function === MatchGameFunction::DELEGATE;
        $criteria->isReferee         = $function === MatchGameFunction::REFEREE;
        $criteria->isRefereeObserver = $function === MatchGameFunction::REFEREE_OBSERVER;

        $rows = $this->responseFactory->fromCriteria($criteria);

        return new DataTable(
            $params->draw,
            $this->repository->getTotalCount(),
            $this->repository->countByCriteria($criteria),
            $rows,
        );
    }
}
