<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MatchGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method MatchGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchGame[]    findAll()
 * @method MatchGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchGameRepository extends ServiceEntityRepository
{
    private const PER_PAGE = 10;

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
    ): array {
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
                'DATE_FORMAT(' .
                    'matchGame.dateTime, ' .
                    '\'%d.%m.%Y, %H:%i\'' .
                ') LIKE :search' .
                ' OR ' .
                '(gameType.group IS NULL AND gameType.name LIKE :search)' .
                ' OR ' .
                'CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :search' .
                ' OR ' .
                'CONCAT(COALESCE(homeTeam.fullName, \'\'), \' - \', COALESCE(awayTeam.fullName, \'\')) LIKE :search'
            );
            $query->setParameter('search', '%' . $search['value'] . '%');
        }

        foreach ($columns as $colKey => $column) {
            if ($column['search']['value'] != '') {
                $searchItem  = $column['search']['value'];
                $searchQuery = null;

                switch ($column['name']) {
                    case 'dateTime':
                        {
                            $searchQuery =
                                'DATE_FORMAT(' .
                                    'matchGame.dateTime, ' .
                                    '\'%d.%m.%Y, %H:%i\'' .
                                ') LIKE :item_' . $colKey;

                            break;
                        }
                    case 'gameType':
                        {
                            $searchQuery =
                                '(gameType.group IS NULL AND gameType.name LIKE :item_' . $colKey . ')' .
                                ' OR ' .
                                '(CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :item_' . $colKey . ')';

                            break;
                        }
                    case 'teams':
                        {
                            $searchQuery =
                                'CONCAT(homeTeam.fullName, \' - \', awayTeam.fullName) LIKE :item_' . $colKey;

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
                }

                if ($orderColumn !== null) {
                    $query->orderBy($orderColumn, $order['dir']);
                }
            }
        }

        $query->addOrderBy('matchGame.dateTime', 'DESC');

        $results     = $query->getQuery()->getResult();
        $countResult = $countQuery->getQuery()->getSingleScalarResult();

        return [
            'results'     => $results,
            'countResult' => $countResult,
        ];
    }

    public function findByCriteria(Criteria $criteria): PagerfantaInterface
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getMatchGameQueryBuilder($criteria)
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

    private function getMatchGameQueryBuilder(Criteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('mg')
            ->leftJoin('mg.homeTeam', 't1')
            ->leftJoin('mg.awayTeam', 't2')
            ->leftJoin('mg.gameType', 'gt')
            ->addOrderBy('mg.dateTime', 'DESC');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, Criteria $criteria): QueryBuilder
    {
        if ($criteria->dateTimeLike) {
            $qb->andWhere(
                'DATE_FORMAT(' .
                    'mg.dateTime, ' .
                    '\'%d.%m.%Y, %H:%i\'' .
                ') LIKE :dateTime'
            )->setParameter('dateTime', '%' . $criteria->dateTimeLike . '%');
        }
        if ($criteria->gameTypeLike) {
            $qb->andWhere(
                'gt.group IS NULL AND gt.name LIKE :gameType' .
                ' OR ' .
                'CONCAT(gt.name, \' Grupa \', gt.group) LIKE :gameType'
            )->setParameter('gameType', '%' . $criteria->gameTypeLike . '%');
        }
        if ($criteria->teamsLike) {
            $qb->andWhere(
                'CONCAT(t1.fullName, \' - \', t2.fullName) LIKE :teams'
            )->setParameter('teams', '%' . $criteria->teamsLike . '%');
        }

        return $qb;
    }

    public function hydrate(MatchGame ...$matchGames): void
    {
        $this->_em->createQueryBuilder()
            ->select('mg')
            ->addSelect('t1')
            ->addSelect('t2')
            ->addSelect('gt')
            ->from(MatchGame::class, 'mg')
            ->leftJoin('mg.homeTeam', 't1')
            ->leftJoin('mg.awayTeam', 't2')
            ->leftJoin('mg.gameType', 'gt')
            ->where('mg IN (?1)')
            ->setParameter(1, $matchGames)
            ->getQuery()
            ->getResult();
    }
}
