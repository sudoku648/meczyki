<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\DataTable\Provider;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Sudoku648\Meczyki\Team\Domain\Persistence\TeamRepositoryInterface;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Factory\DataTableTeamCriteriaFactory;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Factory\DataTableTeamResponseFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class TeamDataTableProvider
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private TeamRepositoryInterface $repository,
        private DataTableTeamResponseFactory $responseFactory,
    ) {
    }

    public function provide(): DataTable
    {
        if (!$this->security->isGranted(TeamVoter::LIST)) {
            return new DataTable();
        }

        $params = DataTableParamsFactory::fromRequest($this->requestStack->getCurrentRequest());

        $criteria = DataTableTeamCriteriaFactory::fromParams($params);

        $rows = $this->responseFactory->fromCriteria($criteria);

        return new DataTable(
            $params->draw,
            $this->repository->getTotalCount(),
            $this->repository->countByCriteria($criteria),
            $rows,
        );
    }
}
