<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GameType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method GameType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameType[]    findAll()
 * @method GameType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameTypeRepository extends ServiceEntityRepository
{
    private const PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameType::class);
    }

    public function getRequiredDTData(
        string $start,
        string $length,
        array $orders,
        array $search,
        array $columns,
        ?string $otherConditions = null
    ): array {
        $query = $this->createQueryBuilder('gameType');

        $countQuery = $this->createQueryBuilder('gameType');
        $countQuery->select('COUNT(gameType)');

        if ($otherConditions === null) {
            $query->where('1=1');
            $countQuery->where('1=1');
        } else {
            $query->where($otherConditions);
            $countQuery->where($otherConditions);
        }

        if ($search['value'] != '') {
            $query->andWhere(
                '(gameType.group IS NULL AND gameType.name LIKE :search)' .
                ' OR ' .
                '(CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :search)'
            );
            $query->setParameter('search', '%' . $search['value'] . '%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem  = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'name':
                        {
                            $searchQuery = '(gameType.group IS NULL AND gameType.name LIKE :item_' . $colKey . ')';
                            $searchQuery .= ' OR ';
                            $searchQuery .= '(CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :item_' . $colKey . ')';

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
                            $orderColumn = 'gameType.name';

                            break;
                        }
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('gameType.name', 'ASC');
        $query->addOrderBy('gameType.group + 0', 'ASC');

        $results     = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
    }

    public function allOrderedByName(): array
    {
        $query = $this->createQueryBuilder('gameType');

        $query->addOrderBy('gameType.name', 'ASC');
        $query->addOrderBy('gameType.group + 0', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function findByCriteria(Criteria $criteria): PagerfantaInterface
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getGameTypeQueryBuilder($criteria)
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

    private function getGameTypeQueryBuilder(Criteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('gt')
            ->addOrderBy('gt.name', 'ASC')
            ->addOrderBy('gt.group + 0', 'ASC');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, Criteria $criteria): QueryBuilder
    {
        if ($criteria->nameLike) {
            $qb->andWhere(
                'gt.group IS NULL AND gt.name LIKE :name' .
                ' OR ' .
                'CONCAT(gt.name, \' Grupa \', gt.group) LIKE :name'
            )->setParameter('name', '%' . $criteria->nameLike . '%');
        }

        return $qb;
    }

    public function hydrate(GameType ...$gameTypes): void
    {
        $this->_em->createQueryBuilder()
            ->select('gt')
            ->addSelect('i')
            ->from(GameType::class, 'gt')
            ->leftJoin('gt.image', 'i')
            ->where('gt IN (?1)')
            ->setParameter(1, $gameTypes)
            ->getQuery()
            ->getResult();
    }
}
