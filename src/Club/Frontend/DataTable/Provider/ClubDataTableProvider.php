<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\DataTable\Provider;

use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Club\Frontend\DataTable\Factory\DataTableClubCriteriaFactory;
use Sudoku648\Meczyki\Club\Frontend\DataTable\Factory\DataTableClubResponseFactory;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class ClubDataTableProvider
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private ClubRepositoryInterface $repository,
        private DataTableClubResponseFactory $responseFactory,
    ) {
    }

    public function provide(): DataTable
    {
        if (!$this->security->isGranted(ClubVoter::LIST)) {
            return new DataTable();
        }

        $params = DataTableParamsFactory::fromRequest($this->requestStack->getCurrentRequest());

        $criteria = DataTableClubCriteriaFactory::fromParams($params);

        $rows = $this->responseFactory->fromCriteria($criteria);

        return new DataTable(
            $params->draw,
            $this->repository->getTotalCount(),
            $this->repository->countByCriteria($criteria),
            $rows,
        );
    }
}
