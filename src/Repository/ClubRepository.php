<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
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
        $query = $this->createQueryBuilder('club');

        $countQuery = $this->createQueryBuilder('club');
        $countQuery->select('COUNT(club)');

        if ($otherConditions === null) {
            $query->where('1=1');
            $countQuery->where('1=1');
        } else {
            $query->where($otherConditions);
            $countQuery->where($otherConditions);
        }

        if ($search['value'] != '') {
            $query->andWhere('club.name LIKE :search');
            $query->setParameter('search', '%'.$search['value'].'%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'name':
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
                        $orderColumn = 'club.name';
                        break;
                    }
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('club.name', 'ASC');

        $results = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
    }
}
