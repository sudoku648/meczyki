<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Frontend\DataTable\Factory\DataTableGameTypeCriteriaFactory;
use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView\GameTypePageView;

/**
 * @method GameType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameType[]    findAll()
 * @method GameType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineGameTypeRepository extends ServiceEntityRepository implements GameTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameType::class);
    }

    public function persist(GameType $gameType): void
    {
        $this->_em->persist($gameType);
        $this->_em->flush();
    }

    public function remove(GameType $gameType): void
    {
        $this->_em->remove($gameType);
        $this->_em->flush();
    }

    public function allOrderedByName(): array
    {
        $query = $this->createQueryBuilder('gameType');

        $query->addOrderBy('gameType.name', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('gameType')
            ->select('COUNT(gameType)')
            ->getQuery()->getSingleScalarResult();
    }

    public function countByCriteria(GameTypePageView $criteria): int
    {
        $qb = $this->getGameTypeQueryBuilder($criteria);

        $qb->select('COUNT(gameType)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCriteria(GameTypePageView $criteria): array
    {
        $pagerfanta = new Pagerfanta(
            new QueryAdapter(
                $this->getGameTypeQueryBuilder($criteria)
            )
        );

        try {
            $pagerfanta->setMaxPerPage($criteria->perPage ?? DataTableGameTypeCriteriaFactory::PER_PAGE);
            $pagerfanta->setCurrentPage($criteria->page);
        } catch (NotValidCurrentPageException $e) {
            $pagerfanta->setCurrentPage(1);
        }

        $this->hydrate(...$pagerfanta->getCurrentPageResults());

        return (array) $pagerfanta->getCurrentPageResults();
    }

    private function getGameTypeQueryBuilder(GameTypePageView $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('gameType');

        $this->filter($qb, $criteria);

        return $qb;
    }

    private function filter(QueryBuilder $qb, GameTypePageView $criteria): QueryBuilder
    {
        if ('' !== $criteria->globalSearch) {
            $qb->andWhere(
                'gameType.name LIKE :search'
            )->setParameter('search', '%' . $criteria->globalSearch . '%');
        }
        if ('' !== $criteria->nameLike) {
            $qb->andWhere(
                'gameType.name LIKE :name'
            )->setParameter('name', '%' . $criteria->nameLike . '%');
        }

        switch ($criteria->sortColumn) {
            default:
            case DataTableGameTypeCriteriaFactory::SORT_NAME:
                $qb->addOrderBy('gameType.name', $criteria->sortDirection);

                break;
        }

        return $qb;
    }

    public function hydrate(GameType ...$gameType): void
    {
        $this->createQueryBuilder('gameType')
            ->addSelect('image')
            ->leftJoin('gameType.image', 'image')
            ->where('gameType IN (?1)')
            ->setParameter(1, $gameType)
            ->getQuery()
            ->getResult();
    }
}
