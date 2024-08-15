<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubId;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Club\Frontend\DataTable\Factory\DataTableClubCriteriaFactory;
use Sudoku648\Meczyki\Club\Infrastructure\Persistence\PageView\ClubPageView;

/**
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineClubRepository extends ServiceEntityRepository implements ClubRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function persist(Club $club): void
    {
        $this->_em->persist($club);
        $this->_em->flush();
    }

    public function remove(Club $club): void
    {
        $this->_em->remove($club);
        $this->_em->flush();
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('club')
            ->select('COUNT(club)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(ClubPageView $criteria): int
    {
        $qb = $this->getClubQueryBuilder($criteria);

        $qb->select('COUNT(club)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(ClubPageView $criteria): array
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getClubQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? DataTableClubCriteriaFactory::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return (array) $pagerfanta->getCurrentPageResults();
    }

    private function getClubQueryBuilder(ClubPageView $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('club');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, ClubPageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(
                'club.name LIKE :search'
            )->setParameter('search', "%$criteria->globalSearch%");
        }
        if ('' !== $criteria->nameLike) {
            $qb->andWhere(
                'club.name LIKE :name'
            )->setParameter('name', "%$criteria->nameLike%");
        }

        switch ($criteria->sortColumn) {
            default:
            case DataTableClubCriteriaFactory::SORT_NAME:
                $sortColumn = 'club.name';

                break;
        }

        $qb->addOrderBy($sortColumn, $criteria->sortDirection);

        return $qb;
    }

    public function hydrate(Club ...$club): void
    {
        $this->createQueryBuilder('club')
            ->addSelect('image')
            ->leftJoin('club.emblem', 'image')
            ->where('club IN (?1)')
            ->setParameter(1, $club)
            ->getQuery()
            ->getResult();
    }

    public function existsWithNameAndId(ClubName $name, ?ClubId $clubId): bool
    {
        $qb = $this->createQueryBuilder('club')
            ->where('club.name = :name')
            ->setParameter('name', $name->getValue());

        if ($clubId) {
            $qb
                ->andWhere('club.id <> :id')
                ->setParameter('id', $clubId->getValue());
        }

        return null !== $qb->getQuery()->getOneOrNullResult();
    }
}
