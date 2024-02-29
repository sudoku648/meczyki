<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Provider;

use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Factory\DataTableMatchGameCriteriaFactory;
use Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Factory\DataTableMatchGameResponseFactory;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class MatchGameDataTableProvider
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private MatchGameRepositoryInterface $repository,
        private DataTableMatchGameResponseFactory $responseFactory,
    ) {
    }

    public function provide(): DataTable
    {
        if (!$this->security->isGranted(MatchGameVoter::LIST)) {
            return new DataTable();
        }

        $params = DataTableParamsFactory::fromRequest($this->requestStack->getCurrentRequest());

        $criteria = DataTableMatchGameCriteriaFactory::fromParams($params);

        $rows = $this->responseFactory->fromCriteria($criteria);

        return new DataTable(
            $params->draw,
            $this->repository->getTotalCount(),
            $this->repository->countByCriteria($criteria),
            $rows,
        );
    }
}
