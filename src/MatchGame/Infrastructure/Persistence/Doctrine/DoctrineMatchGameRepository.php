<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Factory\DataTableMatchGameCriteriaFactory;
use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\PageView\MatchGamePageView;

/**
 * @method MatchGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchGame[]    findAll()
 * @method MatchGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineMatchGameRepository extends ServiceEntityRepository implements MatchGameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchGame::class);
    }

    public function persist(MatchGame $matchGame): void
    {
        $this->_em->persist($matchGame);
        $this->_em->flush();
    }

    public function remove(MatchGame $matchGame): void
    {
        $this->_em->remove($matchGame);
        $this->_em->flush();
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('matchGame')
            ->select('COUNT(matchGame)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(MatchGamePageView $criteria): int
    {
        $qb = $this->getMatchGameQueryBuilder($criteria);

        $qb->select('COUNT(matchGame)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(MatchGamePageView $criteria): array
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getMatchGameQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? DataTableMatchGameCriteriaFactory::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return (array) $pagerfanta->getCurrentPageResults();
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
                'gameType.name LIKE :search' .
                ' OR ' .
                'CONCAT(COALESCE(homeTeam.name, \'\'), \' - \', COALESCE(awayTeam.name, \'\')) LIKE :search'
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
                'gameType.name LIKE :gameType'
            )->setParameter('gameType', '%' . $criteria->gameTypeLike . '%');
        }
        if ($criteria->teamsLike) {
            $qb->andWhere(
                'CONCAT(homeTeam.name, \' - \', awayTeam.name) LIKE :teams'
            )->setParameter('teams', '%' . $criteria->teamsLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case DataTableMatchGameCriteriaFactory::SORT_DATETIME:
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
