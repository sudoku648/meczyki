<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Persistence\TeamRepositoryInterface;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Factory\DataTableTeamCriteriaFactory;
use Sudoku648\Meczyki\Team\Infrastructure\Persistence\PageView\TeamPageView;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineTeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function persist(Team $team): void
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    public function remove(Team $team): void
    {
        $this->_em->remove($team);
        $this->_em->flush();
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

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('team')
            ->select('COUNT(team)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(TeamPageView $criteria): int
    {
        $qb = $this->getTeamQueryBuilder($criteria);

        $qb->select('COUNT(team)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(TeamPageView $criteria): array
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getTeamQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? DataTableTeamCriteriaFactory::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return (array) $pagerfanta->getCurrentPageResults();
    }

    private function getTeamQueryBuilder(Criteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('team')
            ->join('team.club', 'club');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, TeamPageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(sprintf(
                '%s OR %s OR %s',
                'team.name LIKE :search',
                'team.shortName LIKE :search',
                'club.name LIKE :search',
            ))->setParameter('search', "%$criteria->globalSearch%");
        }
        if ($criteria->club) {
            $qb->andWhere('team.club = :club')
                ->setParameter('club', $criteria->club);
        }
        if ('' !== $criteria->nameLike) {
            $qb->andWhere(sprintf(
                '%s OR %s',
                'team.name LIKE :name',
                'team.shortName LIKE :name',
            ))->setParameter('name', "%$criteria->nameLike%");
        }
        if ($criteria->clubNameLike) {
            $qb->andWhere('club.name LIKE :clubName')
                ->setParameter('clubName', "%$criteria->clubNameLike%");
        }

        switch ($criteria->sortColumn) {
            default:
            case DataTableTeamCriteriaFactory::SORT_SHORT_NAME:
                $sortColumn = 'team.shortName';

                break;
            case DataTableTeamCriteriaFactory::SORT_CLUB_NAME:
                $sortColumn = 'club.name';

                break;
        }

        $qb->addOrderBy($sortColumn, $criteria->sortDirection);

        return $qb;
    }

    public function hydrate(Team ...$team): void
    {
        $this->createQueryBuilder('team')
            ->addSelect('club')
            ->join('team.club', 'club')
            ->where('team IN (?1)')
            ->setParameter(1, $team)
            ->getQuery()
            ->getResult();
    }
}
