<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MatchGame;
use App\PageView\MatchGamePageView;
use App\Repository\Contracts\MatchGameRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

/**
 * @method MatchGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchGame[]    findAll()
 * @method MatchGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineMatchGameRepository extends ServiceEntityRepository implements MatchGameRepositoryInterface
{
    public const SORT_DATETIME = 'dateTime';
    public const SORT_DIR_ASC  = 'ASC';
    public const SORT_DIR_DESC = 'DESC';

    public const SORT_DEFAULT     = self::SORT_DATETIME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_DESC;
    public const PER_PAGE         = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchGame::class);
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('matchGame')
            ->select('COUNT(matchGame)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(MatchGamePageView|Criteria $criteria): int
    {
        $qb = $this->getMatchGameQueryBuilder($criteria);

        $qb->select('COUNT(matchGame)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(MatchGamePageView|Criteria $criteria): PagerfantaInterface
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
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return $pagerfanta;
    }

    private function getMatchGameQueryBuilder(MatchGamePageView $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('matchGame')
            ->leftJoin('matchGame.homeTeam', 'homeTeam')
            ->leftJoin('matchGame.awayTeam', 'awayTeam')
            ->leftJoin('matchGame.gameType', 'gameType');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, MatchGamePageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(
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
            )->setParameter('search', '%' . $criteria->globalSearch . '%');
        }
        if ($criteria->dateTimeLike) {
            $qb->andWhere(
                'DATE_FORMAT(' .
                    'matchGame.dateTime, ' .
                    '\'%d.%m.%Y, %H:%i\'' .
                ') LIKE :dateTime'
            )->setParameter('dateTime', '%' . $criteria->dateTimeLike . '%');
        }
        if ($criteria->gameTypeLike) {
            $qb->andWhere(
                'gameType.group IS NULL AND gameType.name LIKE :gameType' .
                ' OR ' .
                'CONCAT(gameType.name, \' Grupa \', gameType.group) LIKE :gameType'
            )->setParameter('gameType', '%' . $criteria->gameTypeLike . '%');
        }
        if ($criteria->teamsLike) {
            $qb->andWhere(
                'CONCAT(homeTeam.fullName, \' - \', awayTeam.fullName) LIKE :teams'
            )->setParameter('teams', '%' . $criteria->teamsLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case self::SORT_DATETIME:
                $sortColumn = 'matchGame.dateTime';

                break;
        }

        $qb->addOrderBy($sortColumn, $criteria->sortDirection);

        return $qb;
    }

    public function hydrate(MatchGame ...$matchGame): void
    {
        $this->createQueryBuilder('matchGame')
            ->addSelect('homeTeam')
            ->addSelect('awayTeam')
            ->addSelect('gameType')
            ->leftJoin('matchGame.homeTeam', 'homeTeam')
            ->leftJoin('matchGame.awayTeam', 'awayTeam')
            ->leftJoin('matchGame.gameType', 'gameType')
            ->where('matchGame IN (?1)')
            ->setParameter(1, $matchGame)
            ->getQuery()
            ->getResult();
    }
}
