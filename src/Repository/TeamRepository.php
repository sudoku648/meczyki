<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
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
        $query = $this->createQueryBuilder('team');

        $countQuery = $this->createQueryBuilder('team');
        $countQuery->select('COUNT(team)');

        $query
            ->join('team.club', 'club');

        $countQuery
            ->join('team.club', 'club');

        if ($otherConditions === null) {
            $query->where('1=1');
            $countQuery->where('1=1');
        } else {
            $query->where($otherConditions);
            $countQuery->where($otherConditions);
        }

        if ($search['value'] != '') {
            $query->andWhere(
                'team.fullName LIKE :search'.
                ' OR '.
                'team.shortName LIKE :search'.
                ' OR '.
                'club.name LIKE :search'
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
                        $searchQuery =
                            'team.fullName LIKE :item_'.$colKey.
                            ' OR '.
                            'team.shortName LIKE :item_'.$colKey;
                        break;
                    }
                    case 'club':
                    {
                        $searchQuery = 'club.name LIKE :item_'.$colKey;
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
                        $orderColumn = 'team.shortName';
                        break;
                    }
                    case 'club':
                    {
                        $orderColumn = 'club.name';
                        break;
                    }
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('team.shortName', 'ASC');

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
                'shortName' => 'ASC',
            ]
        );
    }
}
