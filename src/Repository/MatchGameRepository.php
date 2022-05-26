<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MatchGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchGame[]    findAll()
 * @method MatchGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchGame::class);
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
        $query = $this->createQueryBuilder('matchGame');

        $countQuery = $this->createQueryBuilder('matchGame');
        $countQuery->select('COUNT(matchGame)');

        $query
            ->leftJoin('matchGame.homeTeam', 'homeTeam')
            ->leftJoin('matchGame.awayTeam', 'awayTeam')
            ->leftJoin('matchGame.gameType', 'gameType');

        $countQuery
            ->leftJoin('matchGame.homeTeam', 'homeTeam')
            ->leftJoin('matchGame.awayTeam', 'awayTeam')
            ->leftJoin('matchGame.gameType', 'gameType');

        if ($otherConditions === null) {
            $query->where('1=1');
            $countQuery->where('1=1');
        } else {
            $query->where($otherConditions);
            $countQuery->where($otherConditions);
        }

        if ($search['value'] != '') {
            $query->andWhere(
                'DATE_FORMAT('.
                    'matchGame.dateTime, '.
                    '\'%d.%m.%Y, %H:%i\''.
                ') LIKE :search'.
                ' OR '.
                '(gameType.group IS NULL AND gameType.name LIKE :search)'.
                ' OR '.
                'CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :search'.
                ' OR '.
                'CONCAT(COALESCE(homeTeam.fullName, \'\'), \' - \', COALESCE(awayTeam.fullName, \'\')) LIKE :search'
            );
            $query->setParameter('search', '%'.$search['value'].'%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'dateTime':
                    {
                        $searchQuery =
                            'DATE_FORMAT('.
                                'matchGame.dateTime, '.
                                '\'%d.%m.%Y, %H:%i\''.
                            ') LIKE :item_'.$colKey;
                        break;
                    }
                    case 'gameType':
                    {
                        $searchQuery =
                            '(gameType.group IS NULL AND gameType.name LIKE :item_'.$colKey.')'.
                            ' OR '.
                            '(CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :item_'.$colKey.')';
                        break;
                    }
                    case 'teams':
                    {
                        $searchQuery =
                            'CONCAT(homeTeam.fullName, \' - \', awayTeam.fullName) LIKE :item_'.$colKey;
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
                    
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('matchGame.dateTime', 'DESC');

        $results = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
    }
}
