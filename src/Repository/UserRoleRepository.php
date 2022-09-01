<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method UserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRole[]    findAll()
 * @method UserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoleRepository extends ServiceEntityRepository
{
    private const PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRole::class);
    }

    public function getRequiredDTData(
        string $start,
        string $length,
        array $orders,
        array $search,
        array $columns,
        ?string $otherConditions = null
    ): array {
        $query = $this->createQueryBuilder('userRole');

        $countQuery = $this->createQueryBuilder('userRole');
        $countQuery->select('COUNT(userRole)');

        if ($otherConditions === null) {
            $query->where('1=1');
            $countQuery->where('1=1');
        } else {
            $query->where($otherConditions);
            $countQuery->where($otherConditions);
        }

        if ($search['value'] != '') {
            $query->andWhere('userRole.name LIKE :search');
            $query->setParameter('search', '%' . $search['value'] . '%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem  = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'name':
                        {
                            $searchQuery = 'userRole.name LIKE :item_' . $colKey;

                            break;
                        }
                }

                if ($searchQuery !== null) {
                    $query->andWhere($searchQuery);
                    $query->setParameter('item_' . $colKey, '%' . $searchItem . '%');
                    $countQuery->andWhere($searchQuery);
                    $countQuery->setParameter('item_' . $colKey, '%' . $searchItem . '%');
                }
            }
        }

        if ($length < 0) {
            $length = null;
        }

        $query->setFirstResult($start)->setMaxResults($length);

        foreach ($orders as $key => $order) {
            if ($order['name'] != '') {
                $orderColumn = null;

                switch ($order['name']) {
                    case 'name':
                        {
                            $orderColumn = 'userRole.name';

                            break;
                        }
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('userRole.name', 'ASC');

        $results     = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
    }

    public function allOrderedByName(): array
    {
        $query = $this->createQueryBuilder('userRole');

        $query->addOrderBy('userRole.name', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function findByCriteria(Criteria $criteria): PagerfantaInterface
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
            throw new NotFoundHttpException();
        }

        $this->hydrate(...$pagerfanta);

        return $pagerfanta;
    }

    private function getUserRoleQueryBuilder(Criteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('ur')
            ->addOrderBy('ur.name', 'ASC');

        return $qb;
    }

    public function hydrate(UserRole ...$userRoles): void
    {
        $this->_em->createQueryBuilder()
            ->select('ur')
            ->from(UserRole::class, 'ur')
            ->where('ur IN (?1)')
            ->setParameter(1, $userRoles)
            ->getQuery()
            ->getResult();
    }
}
