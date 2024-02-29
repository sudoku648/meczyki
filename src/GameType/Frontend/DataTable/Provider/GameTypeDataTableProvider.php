<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\DataTable\Provider;

use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Frontend\DataTable\Factory\DataTableGameTypeCriteriaFactory;
use Sudoku648\Meczyki\GameType\Frontend\DataTable\Factory\DataTableGameTypeResponseFactory;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class GameTypeDataTableProvider
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private GameTypeRepositoryInterface $repository,
        private DataTableGameTypeResponseFactory $responseFactory,
    ) {
    }

    public function provide(): DataTable
    {
        if (!$this->security->isGranted(GameTypeVoter::LIST)) {
            return new DataTable();
        }

        $params = DataTableParamsFactory::fromRequest($this->requestStack->getCurrentRequest());

        $criteria = DataTableGameTypeCriteriaFactory::fromParams($params);

        $rows = $this->responseFactory->fromCriteria($criteria);

        return new DataTable(
            $params->draw,
            $this->repository->getTotalCount(),
            $this->repository->countByCriteria($criteria),
            $rows,
        );
    }
}
