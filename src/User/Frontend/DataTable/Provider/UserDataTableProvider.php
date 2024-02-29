<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\DataTable\Provider;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Frontend\DataTable\Factory\DataTableUserCriteriaFactory;
use Sudoku648\Meczyki\User\Frontend\DataTable\Factory\DataTableUserResponseFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class UserDataTableProvider
{
    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private UserRepositoryInterface $repository,
        private DataTableUserResponseFactory $responseFactory,
    ) {
    }

    public function provide(): DataTable
    {
        if (!$this->security->isGranted(UserVoter::LIST)) {
            return new DataTable();
        }

        $params = DataTableParamsFactory::fromRequest($this->requestStack->getCurrentRequest());

        $criteria = DataTableUserCriteriaFactory::fromParams($params);

        $rows = $this->responseFactory->fromCriteria($criteria);

        return new DataTable(
            $params->draw,
            $this->repository->getTotalCount(),
            $this->repository->countByCriteria($criteria),
            $rows,
        );
    }
}
