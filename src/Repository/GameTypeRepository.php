<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GameType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameType[]    findAll()
 * @method GameType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameTypeRepository extends ServiceEntityRepository
{
    const PER_PAGE = 10;

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
    ): array
    {
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
                '(gameType.group IS NULL AND gameType.name LIKE :search)'.
                ' OR '.
                '(CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :search)'
            );
            $query->setParameter('search', '%'.$search['value'].'%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'name':
                    {
                        $searchQuery = '(gameType.group IS NULL AND gameType.name LIKE :item_'.$colKey.')';
                        $searchQuery .= ' OR ';
                        $searchQuery .= '(CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :item_'.$colKey.')';
                        break;
                    }
                }

                if ($searchQuery !== null) {
                    $query->andWhere($searchQuery);
                    $query->setParameter('item_'.$colKey, '%'.$searchItem.'%');
                    $countQuery->andWhere($searchQuery);
                    $countQuery->setParameter('item_'.$colKey, '%'.$searchItem.'%');
                }
            }
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

        $results = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
    }

    public function allOrderedByName(): array
    {
        return $this->findBy(
            [],
            [
                'name' => 'ASC',
                'group' => 'ASC',
            ]
        );
    }
}
