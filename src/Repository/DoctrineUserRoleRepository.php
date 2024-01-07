<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserRole;
use App\PageView\UserRolePageView;
use App\Repository\Contracts\UserRoleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

/**
 * @method UserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRole[]    findAll()
 * @method UserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineUserRoleRepository extends ServiceEntityRepository implements UserRoleRepositoryInterface
{
    public const SORT_NAME     = 'name';
    public const SORT_DIR_ASC  = 'ASC';
    public const SORT_DIR_DESC = 'DESC';

    public const SORT_DEFAULT     = self::SORT_NAME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const PER_PAGE         = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRole::class);
    }

    public function allOrderedByName(): array
    {
        $query = $this->createQueryBuilder('userRole');

        $query->addOrderBy('userRole.name', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('userRole')
            ->select('COUNT(userRole)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(UserRolePageView|Criteria $criteria): int
    {
        $qb = $this->getUserRoleQueryBuilder($criteria);

        $qb->select('COUNT(userRole)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(UserRolePageView|Criteria $criteria): PagerfantaInterface
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getUserRoleQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? self::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return $pagerfanta;
    }

    private function getUserRoleQueryBuilder(UserRolePageView $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('userRole');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, UserRolePageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(
                'userRole.name LIKE :search'
            )->setParameter('search', '%' . $criteria->globalSearch . '%');
        }
        if ('' !== $criteria->nameLike) {
            $qb->andWhere(
                'userRole.name LIKE :name'
            )->setParameter('name', '%' . $criteria->nameLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case self::SORT_NAME:
                $sortColumn = 'userRole.name';

                break;
        }

        $qb->addOrderBy($sortColumn, $criteria->sortDirection);

        return $qb;
    }

    public function hydrate(UserRole ...$userRole): void
    {
        $this->createQueryBuilder('userRole')
            ->where('userRole IN (?1)')
            ->setParameter(1, $userRole)
            ->getQuery()
            ->getResult();
    }
}
