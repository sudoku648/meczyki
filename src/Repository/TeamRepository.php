<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Team;
use App\PageView\TeamPageView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public const SORT_SHORT_NAME = 'shortName';
    public const SORT_CLUB_NAME  = 'club'; // @todo check
    public const SORT_DIR_ASC    = 'ASC';
    public const SORT_DIR_DESC   = 'DESC';

    public const SORT_DEFAULT     = self::SORT_SHORT_NAME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const PER_PAGE         = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
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

    public function countByCriteria(TeamPageView|Criteria $criteria): int
    {
        $qb = $this->getTeamQueryBuilder($criteria);

        $qb->select('COUNT(team)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(TeamPageView $criteria): PagerfantaInterface
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getTeamQueryBuilder($criteria)
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
            $qb->andWhere(
                'team.fullName LIKE :search' .
                ' OR ' .
                'team.shortName LIKE :search' .
                ' OR ' .
                'club.name LIKE :search'
            )->setParameter('search', '%' . $criteria->globalSearch . '%');
        }
        if ($criteria->club) {
            $qb->andWhere('team.club = :club')
                ->setParameter('club', $criteria->club);
        }
        if ('' !== $criteria->nameLike) {
            $qb->andWhere(
                'team.fullName LIKE :name' .
                ' OR ' .
                'team.shortName LIKE :name'
            )->setParameter('name', '%' . $criteria->nameLike . '%');
        }
        if ($criteria->clubNameLike) {
            $qb->andWhere('club.name LIKE :clubName')
                ->setParameter('clubName', '%' . $criteria->clubNameLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case self::SORT_SHORT_NAME:
                $sortColumn = 'team.shortName';

                break;
            case self::SORT_CLUB_NAME:
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
